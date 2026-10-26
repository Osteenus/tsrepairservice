<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VoiceLead;
use App\Support\Normalize;
use App\Voice\BusinessHours;
use App\Voice\RecordVoiceLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Endpoints for voice-service (Bearer VOICE_API_KEY). It never stores business data itself. */
class VoiceApiController extends Controller
{
    public function storeLead(Request $request, RecordVoiceLead $record): JsonResponse
    {
        $data = $request->validate([
            'call_sid' => ['required', 'string', 'max:64'],
            'caller_phone' => ['required', 'string', 'max:50'],
            'caller_name' => ['nullable', 'string', 'max:255'],
            'service_address' => ['nullable', 'string', 'max:255'],
            'appliance_type' => ['nullable', 'string', 'max:120'],
            'appliance_brand' => ['nullable', 'string', 'max:120'],
            'reported_problem' => ['nullable', 'string', 'max:5000'],
            'agent_summary' => ['nullable', 'string', 'max:5000'],
            'consent_to_callback' => ['boolean'],
            'preferred_callback_time' => ['nullable', 'string', 'max:255'],
            'recording_notice_played' => ['boolean'],
            'outcome' => ['nullable', Rule::in(['visit', 'callback', 'transfer', 'timeout', 'hangup'])],
            'transferred' => ['boolean'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date'],
        ]);
        $data = array_map(fn ($value) => is_string($value) ? Normalize::text($value) : $value, $data);

        [$lead, $created] = $record($data);

        return response()->json(['id' => $lead->id, 'status' => $lead->status], $created ? 201 : 200);
    }

    /** Result of a transfer to the technician: an answered transfer means the caller was helped. */
    public function transferResult(Request $request, string $callSid): JsonResponse
    {
        $answered = $request->validate(['answered' => ['required', 'boolean']])['answered'];
        $lead = VoiceLead::where('call_sid', $callSid)->firstOrFail();
        $lead->forceFill(['transferred' => true] + ($answered ? ['status' => 'callback_done'] : []))->save();

        return response()->json(['id' => $lead->id, 'status' => $lead->status]);
    }

    /** What the caller said on the follow-up call; the owner still calls back (or marks it done). */
    public function followupResult(Request $request, VoiceLead $lead): JsonResponse
    {
        $data = $request->validate(['still_needs_help' => ['required', 'boolean'], 'notes' => ['nullable', 'string', 'max:2000']]);
        $lead->forceFill(['followup_notes' => trim(($data['still_needs_help'] ? 'Still needs help. ' : 'No longer needs help. ').($data['notes'] ?? ''))])->save();
        if (! $data['still_needs_help'] && $lead->status === 'callback_due') {
            $lead->forceFill(['status' => 'callback_done'])->save();
        }

        return response()->json(['id' => $lead->id, 'status' => $lead->status]);
    }

    public function customer(Request $request): JsonResponse
    {
        $phone = $request->validate(['phone' => ['required', 'string', 'max:50']])['phone'];
        $id = RecordVoiceLead::customerId($phone);
        abort_unless($id, 404);
        $customer = Customer::with(['appliances' => fn ($query) => $query->latest()->limit(3)])->findOrFail($id);

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'address' => $customer->fullAddress() ?: null,
            'appliances' => $customer->appliances->map(fn ($appliance) => trim(($appliance->brand ?? '').' '.($appliance->type ?? '')))->filter()->values(),
        ]);
    }

    public function availability(): JsonResponse
    {
        return response()->json([
            'technician_available' => BusinessHours::isOpen(),
            'callback_minutes' => config('voice.callback_minutes'),
        ]);
    }
}
