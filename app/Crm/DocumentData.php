<?php

namespace App\Crm;

use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Signature;

/**
 * Document props for the CRM screens (builder, detail, customer presentation).
 */
class DocumentData
{
    public static function make(Document $document): array
    {
        $document->load(['items', 'workOrder.customer', 'workOrder.appliance', 'signatures', 'consent', 'revisionOf']);
        $totals = $document->calculateTotals();
        $job = $document->workOrder;

        return [
            'id' => $document->id,
            'type' => $document->type,
            'title' => $document->title(),
            'number' => $document->number,
            'status' => $document->status,
            'status_label' => Document::STATUSES[$document->status],
            'issued_on' => $document->issued_on ? DocumentPresenter::date($document->issued_on) : null,
            'valid_until' => $document->valid_until ? DocumentPresenter::date($document->valid_until) : null,
            'expired' => $document->valid_until && LocalTime::today()->toDateString() > $document->valid_until->toDateString(),
            'approved_at' => LocalTime::display($document->approved_at, 'M j, Y g:i A'),
            'revision_of' => $document->revisionOf?->only('id', 'number', 'total_cents'),
            'notes' => $document->notes,
            'diagnostic_terms' => $document->snapshot['diagnostic_terms'] ?? config('crm.diagnostic_terms'),
            'discount' => $document->discount_cents / 100,
            'items' => $document->items->map(fn (DocumentItem $item) => [
                'kind' => $item->kind,
                'description' => $item->description,
                'part_number' => $item->part_number,
                'qty' => $item->qty,
                'unit' => $item->unit_cents / 100,
                'amount_cents' => $item->amountCents(),
                'is_warranty' => $item->is_warranty,
                'is_used_or_exchanged' => $item->is_used_or_exchanged,
            ]),
            'totals' => [
                'labor' => $totals->labor,
                'parts' => $totals->parts,
                'tax' => $totals->tax,
                'discount' => $totals->discount,
                'total' => $totals->total,
            ],
            'signatures' => $document->signatures->map(fn (Signature $signature) => [
                'id' => $signature->id,
                'role' => $signature->role,
                'signed_name' => $signature->signed_name,
                'signed_at' => LocalTime::display($signature->signed_at, 'M j, Y g:i A'),
                'url' => route('crm.signatures.show', $signature),
            ]),
            'consent' => $document->consent ? [
                'method' => $document->consent->method,
                'authorized_by' => $document->consent->authorized_by,
                'phone' => $document->consent->phone,
                'consented_at' => LocalTime::display($document->consent->consented_at, 'M j, Y g:i A'),
                'description' => $document->consent->description,
            ] : null,
            'job' => [
                'id' => $job->id,
                'customer_name' => $job->customer->name,
                'customer_phone' => $job->customer->phone,
                'appliance' => $job->appliance?->label(),
                'reported_problem' => $job->reported_problem,
            ],
        ];
    }
}
