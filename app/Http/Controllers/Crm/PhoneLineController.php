<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\VoiceCall;
use App\Voice\PhoneLineSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/** Business line settings (AI / simple transfer / A/B test) and call statistics per variant. */
class PhoneLineController extends Controller
{
    private const PERIODS = [7, 30, 90];

    public function show(Request $request): Response
    {
        $days = in_array($request->integer('days'), self::PERIODS, true) ? $request->integer('days') : 30;
        $settings = PhoneLineSettings::all();
        $calls = VoiceCall::where('created_at', '>=', now()->subDays($days))->get();

        return Inertia::render('Crm/PhoneLine', [
            'settings' => $settings,
            'modes' => PhoneLineSettings::MODES,
            'days' => $days,
            'periods' => self::PERIODS,
            'rateLimited' => $calls->where('rate_limited', true)->count(),
            'variants' => collect(['ai' => 'A: AI assistant', 'transfer' => 'B: Transfer to me', 'other' => 'Backup bot / unknown'])
                ->map(fn ($label, $variant) => $this->stats(
                    $label,
                    $calls->where('rate_limited', false)->filter(fn (VoiceCall $call) => ($call->variant ?? 'other') === $variant),
                    $settings['short_call_seconds'],
                ))
                ->filter(fn ($stats, $variant) => $variant !== 'other' || $stats['calls'] > 0)
                ->values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        PhoneLineSettings::save($request->validate([
            'mode' => ['required', Rule::in(array_keys(PhoneLineSettings::MODES))],
            'ai_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'short_call_seconds' => ['required', 'integer', 'min:1', 'max:300'],
        ]));

        return back()->with('flash.banner', 'Phone line updated. New calls use it within a minute.');
    }

    private function stats(string $label, Collection $calls, int $shortSeconds): array
    {
        $timed = $calls->whereNotNull('duration_seconds');
        $percent = fn (int $part, int $of) => $of ? (int) round($part * 100 / $of) : null;
        $price = config('voice.claude_price');
        $cost = $calls->sum(fn (VoiceCall $call) => ($call->input_tokens * $price['input'] + $call->output_tokens * $price['output']) / 1_000_000);

        return [
            'label' => $label,
            'calls' => $calls->count(),
            'with_duration' => $timed->count(),
            'short' => $short = $timed->where('duration_seconds', '<', $shortSeconds)->count(),
            'short_percent' => $percent($short, $timed->count()),
            'avg_seconds' => $timed->count() ? (int) round($timed->avg('duration_seconds')) : null,
            'leads' => $leads = $calls->where('lead_created', true)->count(),
            'leads_percent' => $percent($leads, $calls->count()),
            'transfer_answered' => $calls->where('transfer_status', 'answered')->count(),
            'transfer_missed' => $calls->where('transfer_status', 'missed')->count(),
            'reached_ai' => $calls->where('reached_ai', true)->count(),
            'timed_out' => $calls->where('timed_out', true)->count(),
            'ai_cost' => round($cost, 2),
        ];
    }
}
