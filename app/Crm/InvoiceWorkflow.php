<?php

namespace App\Crm;

use App\Models\Document;
use App\Models\Payment;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Invoices: created from the authorized estimate, capped by it (BHGS), numbered TS-YYYY-NNNN when issued.
 */
class InvoiceWorkflow
{
    public function __construct(private DocumentNumbers $numbers) {}

    public function create(WorkOrder $job): Document
    {
        $approved = $job->approvedEstimate();
        $declined = $approved ? null : $this->latestIssuedEstimate($job);

        if (! $approved && ! $declined) {
            throw ValidationException::withMessages(['document' => 'Give the customer a written estimate before creating an invoice.']);
        }

        return DB::transaction(function () use ($job, $approved, $declined) {
            $tax = config('crm.tax');
            $source = $approved ?? $declined;

            $invoice = $job->documents()->create([
                'type' => 'invoice',
                'status' => 'draft',
                'estimate_id' => $source->id,
                'service_date' => LocalTime::today()->toDateString(),
                'discount_cents' => $approved ? $approved->discount_cents : 0,
                'charge_tax' => $tax['enabled'],
                'tax_rate' => $tax['enabled'] ? $tax['default_rate'] : null,
                'completed_by' => config('crm.technician_name'),
                'performed_by' => config('crm.technician_name'),
                'work_performed' => $approved ? null : 'Diagnosis only; the customer declined the repair.',
            ]);

            // A declined repair is billed for the service call only.
            $items = $approved ? $approved->items : $declined->items->where('kind', 'service_call');
            foreach ($items as $item) {
                $invoice->items()->create($item->only('position', 'kind', 'description', 'part_number', 'qty', 'unit_cents', 'is_warranty', 'is_used_or_exchanged'));
            }
            $invoice->refreshTotals();

            return $invoice;
        });
    }

    /**
     * Most the customer has agreed to pay before tax: the approved estimate, or the service call if they declined.
     */
    public function limitCents(Document $invoice): ?int
    {
        $job = $invoice->workOrder;

        if ($approved = $job->approvedEstimate()) {
            return $approved->total_cents;
        }

        $declined = $this->latestIssuedEstimate($job);

        return $declined ? $declined->items->where('kind', 'service_call')->sum(fn ($item) => $item->amountCents()) : null;
    }

    /** @return list<string> */
    public function missingBhgsFields(Document $invoice): array
    {
        $job = $invoice->workOrder;
        $customer = $job->customer;
        $appliance = $job->appliance;
        $missing = [];

        foreach ([
            'customer address' => $customer->address_line1 || $job->service_address,
            'appliance make (brand)' => $appliance?->brand,
            'appliance model' => $appliance?->model,
            'appliance serial number' => $appliance?->serial,
            'description of the problem' => $job->reported_problem,
            '"invoice completed by"' => $invoice->completed_by,
            '"repair performed by"' => $invoice->performed_by,
            'at least one line' => $invoice->items()->exists(),
        ] as $label => $value) {
            if (blank($value)) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    public function issue(Document $invoice): Document
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = Document::lockForUpdate()->findOrFail($invoice->id);

            if (! $invoice->isDraft()) {
                return $invoice;
            }

            if ($missing = $this->missingBhgsFields($invoice)) {
                throw ValidationException::withMessages(['document' => 'Required on the invoice: '.implode(', ', $missing).'.']);
            }

            $invoice->refreshTotals();
            $limit = $this->limitCents($invoice);
            $preTax = $invoice->total_cents - $invoice->tax_cents;

            if ($limit === null || $preTax > $limit) {
                throw ValidationException::withMessages(['document' => sprintf(
                    'The invoice (%s before tax) is above what the customer authorized (%s). Get consent with a revised estimate first.',
                    Money::format($preTax),
                    Money::format((int) $limit),
                )]);
            }

            $today = LocalTime::today();
            $invoice->forceFill([
                'number' => $this->numbers->next('invoice', $today->year),
                'status' => 'issued',
                'issued_on' => $today->toDateString(),
                'estimate_id' => $invoice->workOrder->approvedEstimate()?->id ?? $invoice->estimate_id,
                'snapshot' => DocumentSnapshot::make($invoice),
            ])->save();

            $this->syncJobStatus($invoice);

            return $invoice;
        });
    }

    /**
     * Optional customer signature on an issued invoice: accepts the work and, when ticked,
     * confirms the replaced parts were returned. Not required by BHGS, but useful in disputes.
     */
    public function sign(Document $invoice, array $data, SignatureStore $signatures, Request $request): Document
    {
        if ($invoice->isEstimate() || $invoice->status !== 'issued') {
            throw ValidationException::withMessages(['document' => 'Only an issued invoice can be signed.']);
        }
        if ($invoice->signatures()->where('role', 'customer')->exists()) {
            throw ValidationException::withMessages(['document' => 'The customer has already signed this invoice.']);
        }

        return DB::transaction(function () use ($invoice, $data, $signatures, $request) {
            $signatures->store($invoice, 'customer', $data['signature'], $data['signed_name'], $request);
            $invoice->forceFill(['parts_returned' => (bool) ($data['parts_returned'] ?? false)])->save();

            return $invoice;
        });
    }

    public function recordPayment(Document $invoice, array $data, ?int $userId): Payment
    {
        if ($invoice->isEstimate() || ! in_array($invoice->status, ['issued', 'approved'], true)) {
            throw ValidationException::withMessages(['amount' => 'Payments are recorded on an issued invoice.']);
        }

        $payment = $invoice->payments()->create([
            'work_order_id' => $invoice->work_order_id,
            'amount_cents' => $data['amount_cents'],
            'method' => $data['method'],
            'paid_on' => $data['paid_on'],
            'note' => $data['note'] ?? null,
            'recorded_by' => $userId,
        ]);

        $this->syncJobStatus($invoice);

        return $payment;
    }

    public function removePayment(Payment $payment): void
    {
        $payment->delete();
        $this->syncJobStatus($payment->document);
    }

    /** Issued invoice → job completed; fully paid → paid. */
    public function syncJobStatus(Document $invoice): void
    {
        $job = $invoice->workOrder;
        if ($job->status === 'cancelled' || $invoice->status === 'void') {
            return;
        }

        $job->update(['status' => $invoice->paidCents() >= $invoice->total_cents ? 'paid' : 'completed']);
    }

    private function latestIssuedEstimate(WorkOrder $job): ?Document
    {
        return $job->documents()->estimates()->whereIn('status', ['issued', 'approved'])->latest('id')->first();
    }
}
