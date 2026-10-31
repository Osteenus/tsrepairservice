<?php

namespace App\Http\Controllers\Crm;

use App\Crm\LocalTime;
use App\Http\Controllers\Controller;
use App\Models\Appliance;
use App\Models\BlockedNumber;
use App\Models\Call;
use App\Models\Customer;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->toString();

        return Inertia::render('Crm/Customers/Index', [
            'search' => $search,
            'customers' => Customer::search($search)
                ->orderBy('name')
                ->paginate(30)
                ->withQueryString()
                ->through(fn (Customer $customer) => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'city' => $customer->city,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Crm/Customers/Form', ['customer' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customer = Customer::create($this->validated($request));

        return to_route('crm.customers.show', $customer)->with('flash.banner', 'Customer saved.');
    }

    public function show(Customer $customer): Response
    {
        return Inertia::render('Crm/Customers/Show', [
            'customer' => $customer->only('id', 'name', 'phone', 'email', 'address_line1', 'city', 'state', 'zip', 'notes'),
            'appliances' => $customer->appliances()->latest()->get(['id', 'type', 'brand', 'model', 'serial', 'notes']),
            'applianceTypes' => config('crm.appliance_types'),
            'applianceBrands' => collect(config('crm.appliance_brands'))
                ->merge(Appliance::query()->whereNotNull('brand')->distinct()->pluck('brand'))
                ->unique(fn ($brand) => mb_strtolower($brand))
                ->sort(SORT_NATURAL | SORT_FLAG_CASE)
                ->values(),
            'jobs' => $customer->workOrders()->with('appliance')->latest()->get()->map(fn (WorkOrder $job) => [
                'id' => $job->id,
                'status' => $job->status,
                'status_label' => WorkOrder::STATUSES[$job->status],
                'appliance' => $job->appliance?->label(),
                'date' => LocalTime::display($job->scheduled_at ?? $job->created_at, 'M j, Y'),
            ]),
            'calls' => Call::with('customer')->where('customer_id', $customer->id)->orderByDesc('started_at')->limit(30)->get()
                ->map(fn (Call $call) => $call->toListItem(BlockedNumber::pluck('phone_e164')->all())),
            'phoneBlocked' => BlockedNumber::isBlocked($customer->phone),
        ]);
    }

    public function edit(Customer $customer): Response
    {
        return Inertia::render('Crm/Customers/Form', [
            'customer' => $customer->only('id', 'name', 'phone', 'email', 'address_line1', 'city', 'state', 'zip', 'notes'),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request));

        return to_route('crm.customers.show', $customer)->with('flash.banner', 'Customer saved.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[\d\s().+-]*$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['required', 'string', 'size:2'],
            'zip' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
