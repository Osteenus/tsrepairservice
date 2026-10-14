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
        $document->loadMissing(['items', 'signatures', 'consent.signature', 'revisionOf', 'workOrder.customer', 'workOrder.appliance']);
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
                'total' => $totals?->total ?? $document->total_cents,
            ],
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
