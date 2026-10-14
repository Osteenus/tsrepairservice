<?php

namespace App\Http\Controllers\Crm;

use App\Crm\DocumentData;
use App\Crm\EstimateWorkflow;
use App\Crm\SignatureStore;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EstimateController extends Controller
{
    public function store(WorkOrder $job, EstimateWorkflow $workflow): RedirectResponse
    {
        $draft = $job->documents()->estimates()->where('status', 'draft')->first();

        return to_route('crm.documents.edit', $draft ?? $workflow->create($job));
    }

    public function issue(Document $document, EstimateWorkflow $workflow): RedirectResponse
    {
        abort_unless($document->isEstimate(), 404);
        $workflow->issue($document);

        return to_route('crm.documents.show', $document)->with('flash.banner', 'Estimate issued.');
    }

    public function present(Document $document): Response|RedirectResponse
    {
        abort_unless($document->isEstimate(), 404);

        if (! in_array($document->status, ['draft', 'issued'], true)) {
            return to_route('crm.documents.show', $document);
        }

        return Inertia::render('Crm/Documents/Present', ['document' => DocumentData::make($document)]);
    }

    public function approve(Request $request, Document $document, EstimateWorkflow $workflow, SignatureStore $signatures): RedirectResponse
    {
        abort_unless($document->isEstimate(), 404);

        $oral = $document->revision_of_id && $request->input('consent.method') === 'oral';
        $data = $request->validate([
            'signature' => [$oral ? 'nullable' : 'required', 'string', 'max:800000'],
            'signed_name' => [$oral ? 'nullable' : 'required', 'string', 'max:120'],
            'consent.method' => ['nullable', 'in:written,oral'],
            'consent.authorized_by' => [$oral ? 'required' : 'nullable', 'string', 'max:120'],
            'consent.phone' => [$oral ? 'required' : 'nullable', 'string', 'max:40'],
            'consent.consented_at' => [$oral ? 'required' : 'nullable', 'date_format:Y-m-d\TH:i'],
            'consent.description' => [$document->revision_of_id ? 'required' : 'nullable', 'string', 'max:2000'],
        ], [], [
            'consent.authorized_by' => 'who authorized',
            'consent.phone' => 'phone number',
            'consent.consented_at' => 'date and time',
            'consent.description' => 'added work',
        ]);

        $workflow->approve($document, $data, $signatures, $request);

        return to_route('crm.documents.show', $document)->with('flash.banner', 'Estimate approved.');
    }

    public function revise(Document $document, EstimateWorkflow $workflow): RedirectResponse
    {
        return to_route('crm.documents.edit', $workflow->revise($document));
    }
}
