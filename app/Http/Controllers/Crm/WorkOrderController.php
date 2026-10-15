<?php

namespace App\Http\Controllers\Crm;

use App\Crm\LocalTime;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Photo;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class WorkOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $filter = $request->input('filter', 'open');

        $query = WorkOrder::with(['customer', 'appliance'])
            ->when($filter === 'open', fn ($query) => $query->whereIn('status', WorkOrder::OPEN_STATUSES))
            ->when($filter === 'today', fn ($query) => $query->whereBetween('scheduled_at', [
                LocalTime::today()->utc(), LocalTime::today()->endOfDay()->utc(),
            ]));

        $query = $filter === 'all'
            ? $query->latest()
            : $query->orderByRaw('scheduled_at is null')->orderBy('scheduled_at')->latest();

        return Inertia::render('Crm/Jobs/Index', [
            'filter' => $filter,
            'jobs' => $query->paginate(30)->withQueryString()->through(fn (WorkOrder $job) => $this->summary($job)),
        ]);
    }

    public function create(Request $request): Response
    {
        $customer = Customer::with('appliances')->findOrFail($request->integer('customer'));

        return Inertia::render('Crm/Jobs/Form', [
            'job' => null,
            'customer' => ['id' => $customer->id, 'name' => $customer->name, 'address' => $customer->fullAddress()],
            'appliances' => $customer->appliances->map(fn ($appliance) => ['id' => $appliance->id, 'label' => $appliance->label()]),
            'selectedAppliance' => $request->integer('appliance') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['customer_id' => ['required', 'integer', Rule::exists('customers', 'id')->whereNull('deleted_at')]]);
        $customer = Customer::findOrFail($data['customer_id']);

        $workOrder = $customer->workOrders()->create($this->validated($request, $customer));

        return to_route('crm.jobs.show', $workOrder)->with('flash.banner', 'Job created.');
    }

    public function show(WorkOrder $job): Response
    {
        $job->load(['customer', 'appliance', 'photos', 'documents' => fn ($query) => $query->latest('id')]);

        return Inertia::render('Crm/Jobs/Show', [
            'job' => [
                ...$this->summary($job),
                'findings' => $job->findings,
                'notes' => $job->notes,
                'repair_request_id' => $job->repair_request_id,
                'customer' => $job->customer->only('id', 'name', 'phone', 'email'),
                'appliance' => $job->appliance?->only('id', 'type', 'brand', 'model', 'serial'),
                'has_approved_estimate' => $job->documents->contains(fn (Document $document) => $document->isEstimate() && $document->status === 'approved'),
            ],
            'documents' => $job->documents->map(fn (Document $document) => [
                'id' => $document->id,
                'type' => $document->type,
                'title' => $document->title(),
                'status' => $document->status,
                'status_label' => Document::STATUSES[$document->status],
                'total' => $document->total_cents,
                'href' => $document->isDraft() ? route('crm.documents.edit', $document) : route('crm.documents.show', $document),
            ]),
            'photos' => $job->photos->map(fn (Photo $photo) => [
                'id' => $photo->id,
                'url' => route('crm.photos.show', $photo),
                'kind' => Photo::KINDS[$photo->kind],
                'from_customer' => $photo->source === 'customer',
                'caption' => $photo->caption,
            ]),
            'photoKinds' => Photo::KINDS,
            'statuses' => WorkOrder::STATUSES,
            'actions' => WorkOrder::MANUAL_TRANSITIONS[$job->status],
        ]);
    }

    public function edit(WorkOrder $job): Response
    {
        $customer = $job->customer()->with('appliances')->first();

        return Inertia::render('Crm/Jobs/Form', [
            'job' => [
                'id' => $job->id,
                'appliance_id' => $job->appliance_id,
                'reported_problem' => $job->reported_problem,
                'findings' => $job->findings,
                'scheduled_at' => LocalTime::toInput($job->scheduled_at),
                'service_address' => $job->service_address,
                'notes' => $job->notes,
            ],
            'customer' => ['id' => $customer->id, 'name' => $customer->name, 'address' => $customer->fullAddress()],
            'appliances' => $customer->appliances->map(fn ($appliance) => ['id' => $appliance->id, 'label' => $appliance->label()]),
            'selectedAppliance' => $job->appliance_id,
        ]);
    }

    public function update(Request $request, WorkOrder $job): RedirectResponse
    {
        $job->update($this->validated($request, $job->customer));

        return to_route('crm.jobs.show', $job)->with('flash.banner', 'Job saved.');
    }

    public function status(Request $request, WorkOrder $job): RedirectResponse
    {
        $allowed = array_keys(WorkOrder::MANUAL_TRANSITIONS[$job->status]);
        $data = $request->validate(['status' => ['required', Rule::in($allowed)]], [
            'status.in' => $job->status === 'paid'
                ? 'This job is paid and closed. Remove the payment on the invoice to change it.'
                : 'This status cannot be set by hand from "'.WorkOrder::STATUSES[$job->status].'".',
        ]);

        // BHGS: no repair work before the customer authorizes a written estimate.
        if ($data['status'] === 'in_progress' && ! $job->approvedEstimate()) {
            throw ValidationException::withMessages(['status' => 'Get the customer\'s signature on a written estimate before starting the repair.']);
        }

        $job->update($data);

        return back()->with('flash.banner', 'Status: '.WorkOrder::STATUSES[$data['status']].'.');
    }

    private function validated(Request $request, Customer $customer): array
    {
        $data = $request->validate([
            'appliance_id' => ['nullable', 'integer', Rule::exists('appliances', 'id')->where('customer_id', $customer->id)->whereNull('deleted_at')],
            'reported_problem' => ['required', 'string', 'max:5000'],
            'findings' => ['nullable', 'string', 'max:5000'],
            'scheduled_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'service_address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ]);

        $data['scheduled_at'] = LocalTime::fromInput($data['scheduled_at'] ?? null);
        $data['service_address'] = ($data['service_address'] ?? null) ?: ($customer->fullAddress() ?: null);

        return $data;
    }

    private function summary(WorkOrder $job): array
    {
        return [
            'id' => $job->id,
            'status' => $job->status,
            'status_label' => WorkOrder::STATUSES[$job->status],
            'customer_name' => $job->customer->name,
            'appliance' => $job->appliance?->label(),
            'reported_problem' => $job->reported_problem,
            'scheduled' => LocalTime::display($job->scheduled_at),
            'service_address' => $job->service_address,
        ];
    }
}
