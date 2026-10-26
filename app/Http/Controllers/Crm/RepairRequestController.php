<?php

namespace App\Http\Controllers\Crm;

use App\Crm\ConvertRepairRequest;
use App\Crm\LocalTime;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\RepairRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RepairRequestController extends Controller
{
    public function index(Request $request): Response
    {
        $archived = $request->boolean('archived');

        return Inertia::render('Crm/Requests/Index', [
            'archived' => $archived,
            'requests' => RepairRequest::with('contact')
                ->when($archived, fn ($query) => $query->whereNotNull('archived_at'), fn ($query) => $query->whereNull('archived_at'))
                ->latest()
                ->paginate(30)
                ->withQueryString()
                ->through(fn (RepairRequest $repairRequest) => [
                    'id' => $repairRequest->id,
                    'name' => $repairRequest->contact?->name,
                    'phone' => $repairRequest->contact?->phone,
                    'appliance' => $repairRequest->applianceType(),
                    'by_phone' => $repairRequest->source === 'phone',
                    'location' => $repairRequest->location,
                    'received' => LocalTime::display($repairRequest->created_at),
                ]),
        ]);
    }

    public function show(RepairRequest $repairRequest): Response
    {
        $contact = $repairRequest->contact;

        return Inertia::render('Crm/Requests/Show', [
            'request' => [
                'id' => $repairRequest->id,
                'name' => $contact?->name,
                'phone' => $contact?->phone,
                'email' => $contact?->email,
                'appliance' => $repairRequest->applianceType(),
                'brand' => $repairRequest->brand,
                'model' => $repairRequest->model,
                'location' => $repairRequest->location,
                'description' => $repairRequest->description,
                'by_phone' => $repairRequest->source === 'phone',
                'received' => LocalTime::display($repairRequest->created_at),
                'archived' => (bool) $repairRequest->archived_at,
                'work_order_id' => $repairRequest->workOrder?->id,
                'voice' => ($lead = $repairRequest->voiceLead) ? [
                    'status' => $lead->status,
                    'due' => LocalTime::display($lead->callback_due_at),
                    'consent' => $lead->consent_to_callback,
                    'followup_notes' => $lead->followup_notes,
                ] : null,
                'photos' => $repairRequest->photos->map(fn (Photo $photo) => ['id' => $photo->id, 'url' => route('crm.photos.show', $photo)]),
            ],
        ]);
    }

    public function convert(RepairRequest $repairRequest, ConvertRepairRequest $convert): RedirectResponse
    {
        $workOrder = $convert($repairRequest);

        return to_route('crm.jobs.show', $workOrder)->with('flash.banner', 'Customer and job created. Check the address before the visit.');
    }

    /** Owner called the voice-agent lead back: stops the follow-up call and the overdue alert. */
    public function calledBack(RepairRequest $repairRequest): RedirectResponse
    {
        $repairRequest->voiceLead?->forceFill(['status' => 'callback_done'])->save();

        return back()->with('flash.banner', 'Marked as called back.');
    }

    public function archive(RepairRequest $repairRequest): RedirectResponse
    {
        $repairRequest->forceFill(['archived_at' => $repairRequest->archived_at ? null : now()])->save();

        return to_route('crm.requests.index');
    }
}
