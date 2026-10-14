<?php

namespace App\Http\Controllers\Crm;

use App\Crm\InvoiceWorkflow;
use App\Crm\Money;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Payment;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function store(WorkOrder $job, InvoiceWorkflow $workflow): RedirectResponse
    {
        $draft = $job->documents()->invoices()->where('status', 'draft')->first();

        return to_route('crm.documents.edit', $draft ?? $workflow->create($job));
    }

    public function issue(Document $document, InvoiceWorkflow $workflow): RedirectResponse
    {
        abort_if($document->isEstimate(), 404);
        $workflow->issue($document);

        return to_route('crm.documents.show', $document)->with('flash.banner', 'Invoice issued.');
    }

    public function storePayment(Request $request, Document $document, InvoiceWorkflow $workflow): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:100000'],
            'method' => ['required', Rule::in(array_keys(Payment::METHODS))],
            'paid_on' => ['required', 'date_format:Y-m-d'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $workflow->recordPayment($document, [...$data, 'amount_cents' => Money::parse($data['amount'])], $request->user()->id);

        return back()->with('flash.banner', 'Payment recorded.');
    }

    public function destroyPayment(Payment $payment, InvoiceWorkflow $workflow): RedirectResponse
    {
        $workflow->removePayment($payment);

        return back()->with('flash.banner', 'Payment removed.');
    }
}
