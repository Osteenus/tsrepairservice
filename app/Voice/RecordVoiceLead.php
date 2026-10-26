<?php

namespace App\Voice;

use App\Mail\VoiceLeadMail;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\RepairRequest;
use App\Models\VoiceCall;
use App\Models\VoiceLead;
use App\Support\Normalize;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Saves a lead from the voice agent once per call: the voice lead itself, a phone request in the
 * CRM inbox (so the usual Call / Convert buttons work) and a queued email to the owner.
 */
class RecordVoiceLead
{
    /** @return array{VoiceLead, bool} the lead and whether it was created now */
    public function __invoke(array $data): array
    {
        if ($existing = VoiceLead::where('call_sid', $data['call_sid'])->first()) {
            return [$existing, false];
        }

        $phone = Normalize::phone(preg_replace('/^\+1(?=\d{10}$)/', '', $data['caller_phone'])) ?? $data['caller_phone'];
        $consent = (bool) ($data['consent_to_callback'] ?? false);
        // The owner must call back whoever agreed to it, ran out of call time or could not be transferred.
        $due = $consent || in_array($data['outcome'] ?? null, ['timeout', 'transfer'], true);

        try {
            $lead = DB::transaction(function () use ($data, $phone, $consent, $due) {
                $contact = Contact::create(['name' => $data['caller_name'] ?? 'Phone caller', 'phone' => $phone, 'email' => '']);
                $repairRequest = RepairRequest::create([
                    'contact_id' => $contact->id,
                    'service_id' => ApplianceType::serviceId($data['appliance_type'] ?? ''),
                    'location' => isset($data['service_address']) ? mb_substr($data['service_address'], 0, 120) : null,
                    'brand' => isset($data['appliance_brand']) ? mb_substr($data['appliance_brand'], 0, 120) : null,
                    'description' => self::description($data),
                    'source' => 'phone',
                    'call_sid' => $data['call_sid'],
                ]);

                return VoiceLead::create([
                    ...$data,
                    'caller_phone' => $phone,
                    'consent_to_callback' => $consent,
                    // The scheduler's follow-up call additionally needs consent_to_callback.
                    'status' => $due ? 'callback_due' : 'new',
                    'callback_due_at' => $due
                        ? BusinessHours::nextOpening()->addMinutes(config('voice.callback_minutes'))
                        : null,
                    'repair_request_id' => $repairRequest->id,
                    'customer_id' => self::customerId($phone),
                ]);
            });
        } catch (UniqueConstraintViolationException) {
            return [VoiceLead::where('call_sid', $data['call_sid'])->firstOrFail(), false];
        }

        VoiceCall::record($lead->call_sid, ['lead_created' => true]);

        try {
            Mail::to(config('repair.request_recipient'))->queue(new VoiceLeadMail($lead, 'new'));
        } catch (Throwable $exception) {
            report($exception); // The lead is saved and in the CRM inbox.
        }

        return [$lead, true];
    }

    public static function customerId(string $phone): ?int
    {
        $digits = substr(preg_replace('/\D/', '', $phone), -10);
        if (strlen($digits) < 10) {
            return null;
        }

        // Phones are stored formatted, so compare the last 10 digits.
        return Customer::query()->get(['id', 'phone'])
            ->first(fn (Customer $customer) => substr(preg_replace('/\D/', '', (string) $customer->phone), -10) === $digits)
            ?->id;
    }

    private static function description(array $data): string
    {
        $parts = array_filter([
            $data['reported_problem'] ?? null,
            isset($data['agent_summary']) ? 'Agent summary: '.$data['agent_summary'] : null,
            isset($data['preferred_callback_time']) ? 'Preferred call-back time: '.$data['preferred_callback_time'] : null,
        ]);

        return $parts ? implode("\n\n", $parts) : 'The caller did not describe the problem.';
    }
}
