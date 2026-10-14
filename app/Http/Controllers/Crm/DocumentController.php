<?php

namespace App\Http\Controllers\Crm;

use App\Crm\DocumentData;
use App\Crm\EstimateWorkflow;
use App\Crm\Money;
use App\Crm\PdfRenderer;
use App\Crm\PdfRenderingFailed;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Mail\CrmDocumentMail;
use App\Models\Signature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class DocumentController extends Controller
{
    public function show(Document $document): Response
    {
        return Inertia::render('Crm/Documents/Show', ['document' => DocumentData::make($document)]);
    }

    public function edit(Document $document): Response|RedirectResponse
    {
        if (! $document->isDraft()) {
            return to_route('crm.documents.show', $document);
        }

        return Inertia::render('Crm/Documents/Edit', [
            'document' => DocumentData::make($document),
            'kinds' => DocumentItem::KINDS,
        ]);
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        abort_unless($document->isDraft(), 403, 'Issued documents cannot be changed.');

        $data = $request->validate([
            'items' => ['array', 'max:40'],
            'items.*.kind' => ['required', Rule::in(array_keys(DocumentItem::KINDS))],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.part_number' => ['nullable', 'string', 'max:80'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01', 'max:9999'],
            'items.*.unit' => ['required', 'numeric', 'min:0', 'max:100000'],
            'items.*.is_warranty' => ['boolean'],
            'items.*.is_used_or_exchanged' => ['boolean'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'work_performed' => ['nullable', 'string', 'max:2000'],
            'completed_by' => ['nullable', 'string', 'max:120'],
            'performed_by' => ['nullable', 'string', 'max:120'],
            'service_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        DB::transaction(function () use ($document, $data) {
            $document->items()->delete();
            foreach (array_values($data['items'] ?? []) as $position => $item) {
                $warranty = (bool) ($item['is_warranty'] ?? false);
                $document->items()->create([
                    'position' => $position,
                    'kind' => $item['kind'],
                    'description' => $item['description'],
                    'part_number' => $item['kind'] === 'part' ? ($item['part_number'] ?? null) : null,
                    'qty' => round((float) $item['qty'], 2),
                    'unit_cents' => $warranty ? 0 : Money::parse($item['unit']),
                    'is_warranty' => $warranty,
                    'is_used_or_exchanged' => $item['kind'] === 'part' && ($item['is_used_or_exchanged'] ?? false),
                ]);
            }
            $document->fill([
                'discount_cents' => Money::parse($data['discount'] ?? 0),
                'notes' => $data['notes'] ?? null,
            ]);
            if (! $document->isEstimate()) {
                $document->fill([
                    'work_performed' => $data['work_performed'] ?? null,
                    'completed_by' => $data['completed_by'] ?? null,
                    'performed_by' => $data['performed_by'] ?? null,
                    'service_date' => $data['service_date'] ?? $document->service_date,
                ]);
            }
            $document->save();
            $document->refreshTotals();
        });

        return match ($request->input('then')) {
            'show' => to_route('crm.documents.show', $document),
            'present' => to_route('crm.estimates.present', $document),
            default => back()->with('flash.banner', 'Saved.'),
        };
    }

    public function void(Document $document, EstimateWorkflow $workflow): RedirectResponse
    {
        $job = $document->workOrder;
        $workflow->void($document);

        return to_route('crm.jobs.show', $job)->with('flash.banner', $document->trashed() ? 'Draft deleted.' : $document->title().' voided.');
    }

    public function email(Document $document): RedirectResponse
    {
        $email = $document->workOrder->customer->email;

        if ($document->isDraft() || $document->status === 'void') {
            return back()->with('flash.banner', 'Only issued documents can be emailed.')->with('flash.bannerStyle', 'danger');
        }
        if (! $email) {
            return back()->with('flash.banner', 'Add the customer\'s email first.')->with('flash.bannerStyle', 'danger');
        }

        Mail::to($email)->queue(new CrmDocumentMail($document));
        $document->forceFill(['emailed_at' => now()])->save();

        return back()->with('flash.banner', "Sending {$document->number} to {$email}.");
    }

    public function preview(Document $document, PdfRenderer $renderer): HttpResponse
    {
        return response($renderer->html($document));
    }

    public function pdf(Request $request, Document $document, PdfRenderer $renderer): HttpResponse
    {
        try {
            $pdf = $renderer->pdf($document);
        } catch (PdfRenderingFailed $exception) {
            return back()->with('flash.banner', $exception->getMessage())->with('flash.bannerStyle', 'danger');
        }

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($request->boolean('download') ? 'attachment' : 'inline').'; filename="'.$renderer->filename($document).'"',
        ]);
    }

    public function signature(Signature $signature): HttpResponse
    {
        abort_unless(Storage::disk('local')->exists($signature->path), 404);

        return response(Storage::disk('local')->get($signature->path), 200, ['Content-Type' => 'image/png']);
    }
}
