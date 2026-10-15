<?php

namespace App\Crm;

use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Signature;
use Illuminate\Support\Facades\Storage;

/**
 * Builds the variables for the estimate and invoice PDF templates.
 */
class DocumentPresenter
{
    public static function pdfContext(Document $document): array
    {
        $document->loadMissing(['items', 'signatures', 'consent.signature', 'revisionOf', 'estimate', 'payments', 'workOrder.customer', 'workOrder.appliance']);
        $snapshot = $document->snapshot ?? DocumentSnapshot::make($document);
        $totals = $document->isDraft() ? $document->calculateTotals() : null;

        $items = $document->items->map(fn (DocumentItem $item) => [
            'kind' => $item->kind,
            'description' => $item->description,
            'part_number' => $item->part_number,
            'qty' => self::qty($item->qty),
            'unit' => $item->is_warranty ? 0 : $item->unit_cents,
            'amount' => $item->amountCents(),
            'warranty' => $item->is_warranty,
            'used' => $item->is_used_or_exchanged,
        ]);

        $customerSignature = $document->signatures->firstWhere('role', 'customer');
        $notes = $document->notes;
        if ($document->revisionOf) {
            $notes = trim('Revises estimate '.$document->revisionOf->number.'. '.$notes);
        }

        $total = $totals?->total ?? $document->total_cents;
        $paid = (int) $document->payments->sum('amount_cents');
        $used = $document->items->where('is_used_or_exchanged', true)->pluck('description');
        $taxRate = (float) $document->tax_rate;

        return [
            'logo' => 'data:image/png;base64,'.base64_encode(file_get_contents(resource_path('images/crm-logo.png'))),
            'watermark' => match ($document->status) {
                'draft' => 'DRAFT',
                'void' => 'VOID',
                default => null,
            },
            'company' => $snapshot['company'],
            'customer' => $snapshot['customer'],
            'appliance' => $snapshot['appliance'],
            'technician' => $snapshot['technician'],
            'diagnostic_terms' => $snapshot['diagnostic_terms'],
            'warranty' => $snapshot['warranty'],
            'payment_methods' => $snapshot['payment_methods'],
            'number' => $document->number ?? 'DRAFT',
            'date' => self::date($document->issued_on ?? LocalTime::today()),
            'valid_until' => $document->isEstimate()
                ? self::date($document->valid_until ?? BusinessDays::add(LocalTime::today(), config('crm.estimate_valid_business_days')))
                : null,
            'labor_items' => $items->where('kind', '!=', 'part')->values()->all(),
            'part_items' => $items->where('kind', 'part')->values()->all(),
            'notes' => $notes,
            'totals' => [
                'labor' => $totals?->labor ?? $document->labor_cents,
                'parts' => $totals?->parts ?? $document->parts_cents,
                'tax' => $totals?->tax ?? $document->tax_cents,
                'discount' => $totals?->discount ?? $document->discount_cents,
                'total' => $total,
                'paid' => $paid,
                'balance' => $total - $paid,
            ],
            'service_date' => self::date($document->service_date ?? $document->issued_on ?? LocalTime::today()),
            'work_performed' => $document->work_performed,
            'completed_by' => $document->completed_by,
            'performed_by' => $document->performed_by,
            'used_parts_statement' => $used->isEmpty()
                ? 'No used or exchanged parts were installed.'
                : 'Used or exchanged parts were installed: '.$used->implode('; ').'.',
            'estimate_ref' => self::estimateReference($document),
            'payment_method_used' => $document->payments->pluck('method')->unique()
                ->map(fn ($method) => \App\Models\Payment::METHODS[$method] ?? $method)->implode(', '),
            'work_accepted' => $document->work_accepted,
            'customer_initials' => $customerSignature ? self::initials($customerSignature->signed_name) : null,
            'show_tax' => $document->charge_tax,
            'tax_rate_label' => rtrim(rtrim(number_format($taxRate * 100, 3), '0'), '.').'%',
            'customer_signature' => $customerSignature ? self::signature($customerSignature) : null,
            'consent' => $document->consent ? [
                'at' => LocalTime::display($document->consent->consented_at, 'M j, Y g:i A'),
                'description' => $document->consent->description,
                'new_total' => $document->consent->new_total_cents,
                'approved_by' => $document->consent->method === 'oral'
                    ? 'Oral: '.$document->consent->authorized_by.', '.$document->consent->phone
                    : 'Signed: '.$document->consent->authorized_by,
            ] : null,
        ];
    }

    private static function estimateReference(Document $document): ?string
    {
        $estimate = $document->estimate;
        if ($document->isEstimate() || ! $estimate?->number) {
            return null;
        }

        $label = $estimate->revision_of_id ? 'Revised written estimate' : 'Written estimate';
        $authorized = $estimate->status === 'approved' ? 'authorized by customer' : 'repair declined by customer';

        return sprintf('%s no. %s dated %s, %s: %s (excludes tax).',
            $label, $estimate->number, self::date($estimate->issued_on), $authorized, Money::format($estimate->total_cents));
    }

    private static function initials(string $name): string
    {
        return collect(preg_split('/\s+/', trim($name)))->filter()->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)).'.')->implode('');
    }

    public static function date(\DateTimeInterface $date): string
    {
        return $date->format('M j, Y');
    }

    public static function qty(float $qty): string
    {
        return rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.');
    }

    private static function signature(Signature $signature): array
    {
        $png = Storage::disk('local')->get($signature->path);

        return [
            'image' => $png ? 'data:image/png;base64,'.base64_encode($png) : '',
            'name' => $signature->signed_name,
            'at' => LocalTime::display($signature->signed_at, 'M j, Y g:i A'),
        ];
    }
}
