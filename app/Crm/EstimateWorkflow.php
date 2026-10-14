<?php

namespace App\Crm;

use App\Models\Document;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Estimates: draft → issued (numbered, frozen) → approved by the customer, or void.
 */
class EstimateWorkflow
{
    public function __construct(private DocumentNumbers $numbers) {}

    public function create(WorkOrder $job): Document
    {
        return DB::transaction(function () use ($job) {
            $estimate = $job->documents()->create(['type' => 'estimate', 'status' => 'draft']);
            $estimate->items()->create([
                'position' => 0,
                'kind' => 'service_call',
                'description' => config('crm.service_call_description'),
                'qty' => 1,
                'unit_cents' => config('crm.service_call_cents'),
            ]);
            $estimate->refreshTotals();

            return $estimate;
        });
    }

    /** A revised estimate starts as a draft copy of the authorized one. */
    public function revise(Document $estimate): Document
    {
        if (! $estimate->isEstimate() || $estimate->status !== 'approved') {
            throw ValidationException::withMessages(['document' => 'Only an approved estimate can be revised.']);
        }

        return DB::transaction(function () use ($estimate) {
            $revision = $estimate->workOrder->documents()->create([
                'type' => 'estimate',
                'status' => 'draft',
                'revision_of_id' => $estimate->id,
                'discount_cents' => $estimate->discount_cents,
                'notes' => $estimate->notes,
            ]);
            foreach ($estimate->items as $item) {
                $revision->items()->create($item->only('position', 'kind', 'description', 'part_number', 'qty', 'unit_cents', 'is_warranty', 'is_used_or_exchanged'));
            }
            $revision->refreshTotals();

            return $revision;
        });
    }

    public function issue(Document $estimate): Document
    {
        return DB::transaction(function () use ($estimate) {
            $estimate = Document::lockForUpdate()->findOrFail($estimate->id);

            if (! $estimate->isDraft()) {
                return $estimate;
            }
            if ($estimate->items()->count() === 0) {
                throw ValidationException::withMessages(['document' => 'Add at least one line before issuing.']);
            }

            $today = LocalTime::today();
            $estimate->refreshTotals();
            $estimate->forceFill([
                'number' => $this->numbers->next('estimate', $today->year),
                'status' => 'issued',
                'issued_on' => $today->toDateString(),
                'valid_until' => BusinessDays::add($today, config('crm.estimate_valid_business_days'))->toDateString(),
                'snapshot' => DocumentSnapshot::make($estimate),
            ])->save();

            $job = $estimate->workOrder;
            if ($job->status === 'new') {
                $job->update(['status' => 'estimate_sent']);
            }

            return $estimate;
        });
    }

    /**
     * Record the customer's authorization. A revision also needs consent details (BHGS):
     * a signature, or oral consent with who, when, phone and a description of the added work.
     *
     * @param  array{signature?: ?string, signed_name?: ?string, consent?: ?array}  $data
     */
    public function approve(Document $estimate, array $data, SignatureStore $signatures, Request $request): Document
    {
        if (! $estimate->isEstimate() || ! in_array($estimate->status, ['draft', 'issued'], true)) {
            throw ValidationException::withMessages(['document' => 'This estimate cannot be approved.']);
        }

        // One transaction: if the approval fails, a draft is not left issued and its number is not used up.
        return DB::transaction(function () use ($estimate, $data, $signatures, $request) {
            $estimate = $this->issue($estimate);

            if ($estimate->valid_until && LocalTime::today()->toDateString() > $estimate->valid_until->toDateString()) {
                throw ValidationException::withMessages(['document' => 'This estimate expired on '.DocumentPresenter::date($estimate->valid_until).'. Create a new estimate.']);
            }

            $signature = null;
            if (! empty($data['signature'])) {
                $signature = $signatures->store($estimate, 'customer', $data['signature'], $data['signed_name'], $request);
            }

            if ($estimate->revision_of_id) {
                $consent = $data['consent'] ?? [];
                if (! $signature && ($consent['method'] ?? null) !== 'oral') {
                    throw ValidationException::withMessages(['signature' => 'A revised estimate needs the customer\'s signature or recorded oral consent.']);
                }

                $estimate->consent()->create([
                    'method' => $signature ? 'written' : 'oral',
                    'authorized_by' => $signature ? $data['signed_name'] : $consent['authorized_by'],
                    'phone' => $signature ? $estimate->workOrder->customer->phone : $consent['phone'],
                    'consented_at' => $signature ? now() : LocalTime::fromInput($consent['consented_at']),
                    'description' => $consent['description'] ?? 'Revised estimate approved.',
                    'new_total_cents' => $estimate->total_cents,
                    'signature_id' => $signature?->id,
                    'recorded_by' => $request->user()?->id,
                ]);
            } elseif (! $signature) {
                throw ValidationException::withMessages(['signature' => 'The customer must sign the estimate.']);
            }

            $estimate->forceFill(['status' => 'approved', 'approved_at' => now()])->save();

            $job = $estimate->workOrder;
            if (in_array($job->status, ['new', 'estimate_sent'], true)) {
                $job->update(['status' => 'approved']);
            }

            return $estimate;
        });
    }

    public function void(Document $document): void
    {
        if ($document->isDraft()) {
            // Drafts have no number, so removing one leaves no gap.
            $document->delete();

            return;
        }

        $document->forceFill(['status' => 'void'])->save();
    }
}
