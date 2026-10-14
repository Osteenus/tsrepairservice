<?php

namespace App\Http\Controllers\Crm;

use App\Crm\LocalTime;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\RepairRequest;
use App\Models\WorkOrder;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $today = LocalTime::today();

        $jobs = fn ($from, $to) => WorkOrder::with(['customer', 'appliance'])
            ->whereIn('status', WorkOrder::OPEN_STATUSES)
            ->whereBetween('scheduled_at', [$from->utc(), $to->utc()])
            ->orderBy('scheduled_at')
            ->get()
            ->map(fn (WorkOrder $job) => [
                'id' => $job->id,
                'customer_name' => $job->customer->name,
                'appliance' => $job->appliance?->label(),
                'status' => $job->status,
                'status_label' => WorkOrder::STATUSES[$job->status],
                'time' => LocalTime::display($job->scheduled_at, $from->isSameDay($today) && $to->isSameDay($today) ? 'g:i A' : 'D, M j, g:i A'),
                'service_address' => $job->service_address,
            ]);

        $unpaid = Document::invoices()
            ->where('status', 'issued')
            ->with('workOrder.customer')
            ->withSum('payments', 'amount_cents')
            ->orderBy('issued_on')
            ->get()
            ->filter(fn (Document $invoice) => $invoice->total_cents > (int) $invoice->payments_sum_amount_cents)
            ->map(fn (Document $invoice) => [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'customer_name' => $invoice->workOrder->customer->name,
                'issued_on' => $invoice->issued_on->format('M j'),
                'balance' => $invoice->total_cents - (int) $invoice->payments_sum_amount_cents,
            ])
            ->values();

        return Inertia::render('Crm/Dashboard', [
            'today' => $jobs($today, $today->endOfDay()),
            'upcoming' => $jobs($today->addDay(), $today->addDays(7)->endOfDay()),
            'requests' => RepairRequest::with('contact')->whereNull('archived_at')->latest()->limit(5)->get()
                ->map(fn (RepairRequest $request) => [
                    'id' => $request->id,
                    'name' => $request->contact?->name,
                    'appliance' => $request->applianceType(),
                    'location' => $request->location,
                    'received' => LocalTime::display($request->created_at, 'M j, g:i A'),
                ]),
            'newRequestCount' => RepairRequest::whereNull('archived_at')->count(),
            'unpaid' => $unpaid,
            'unpaidTotal' => $unpaid->sum('balance'),
            'todayLabel' => $today->format('l, M j'),
        ]);
    }
}
