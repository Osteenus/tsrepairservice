<?php

namespace App\Http\Controllers\Crm;

use App\Crm\LocalTime;
use App\Http\Controllers\Controller;
use App\Models\BlockedNumber;
use App\Models\Call;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/** CRM → Phone → Calls: call log of the business line, frequent callers and blocked numbers. */
class CallLogController extends Controller
{
    private const PERIODS = ['today' => 'Today', '7' => '7 days', '30' => '30 days'];

    /** Calls shown at most; older ones are found with the search. */
    private const LIMIT = 200;

    /** Callers with at least this many calls in 7 days are marked as repeat callers. */
    public const REPEAT_CALLS = 3;

    public function index(Request $request): Response
    {
        $period = array_key_exists($request->string('period')->toString(), self::PERIODS) ? $request->string('period')->toString() : '7';
        $search = $request->string('search')->trim()->toString();
        $digits = preg_replace('/\D+/', '', $search);
        $blocked = BlockedNumber::orderByDesc('created_at')->get();
        $blockedNumbers = $blocked->pluck('phone_e164')->all();

        $calls = Call::with('customer')
            ->where('started_at', '>=', $period === 'today' ? LocalTime::today()->utc() : now()->subDays((int) $period))
            ->when($digits !== '', fn ($query) => $query->where(fn ($query) => $query
                ->where('from_number', 'like', "%{$digits}%")->orWhere('to_number', 'like', "%{$digits}%")))
            ->orderByDesc('started_at')
            ->limit(self::LIMIT + 1)
            ->get();

        return Inertia::render('Crm/Calls', [
            'period' => $period,
            'periods' => self::PERIODS,
            'search' => $search,
            'calls' => $calls->take(self::LIMIT)->map(fn (Call $call) => $call->toListItem($blockedNumbers))->values(),
            'more' => $calls->count() > self::LIMIT,
            'frequent' => $this->frequentCallers($blockedNumbers),
            'repeatCalls' => self::REPEAT_CALLS,
            'blocked' => $blocked->map(fn (BlockedNumber $number) => [
                'id' => $number->id,
                'display_number' => Call::displayNumber($number->phone_e164),
                'reason' => $number->reason,
                'since' => LocalTime::display($number->created_at, 'M j, Y'),
            ]),
        ]);
    }

    public function block(Request $request): RedirectResponse
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:50'], 'reason' => ['nullable', 'string', 'max:255']]);

        if (! BlockedNumber::block($data['phone'], $data['reason'] ?? null)) {
            return back()->with('flash.banner', 'This number cannot be blocked.')->with('flash.bannerStyle', 'danger');
        }

        return back()->with('flash.banner', 'Blocked. Calls from this number are rejected.');
    }

    public function unblock(BlockedNumber $blockedNumber): RedirectResponse
    {
        $blockedNumber->delete();

        return back()->with('flash.banner', 'Unblocked.');
    }

    /** Incoming numbers with two or more calls in the last 7 days, most calls first. */
    private function frequentCallers(array $blockedNumbers): array
    {
        $rows = Call::where('direction', Call::INBOUND)
            ->where('started_at', '>=', now()->subDays(7))
            ->whereNotNull('from_number')
            ->groupBy('from_number')
            ->havingRaw('count(*) >= 2')
            ->orderByDesc(DB::raw('count(*)'))
            ->orderByDesc(DB::raw('max(started_at)'))
            ->limit(10)
            ->get(['from_number', DB::raw('count(*) as calls'), DB::raw('max(started_at) as last_call'), DB::raw('max(customer_id) as customer_id')]);
        $customers = Customer::withTrashed()->whereIn('id', $rows->pluck('customer_id')->filter())->pluck('name', 'id');

        return $rows->map(fn ($row) => [
            'number' => $row->from_number,
            'display_number' => Call::displayNumber($row->from_number),
            'customer' => $row->customer_id ? ['id' => $row->customer_id, 'name' => $customers[$row->customer_id] ?? null] : null,
            'calls' => (int) $row->calls,
            'last_call' => LocalTime::display(Carbon::parse($row->last_call, 'UTC'), 'D, M j, g:i A'),
            'repeat' => $row->calls >= self::REPEAT_CALLS,
            'blocked' => in_array($row->from_number, $blockedNumbers, true),
        ])->all();
    }
}
