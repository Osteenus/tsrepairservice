<?php

namespace App\Http\Controllers;

use App\Mail\WebFormRequestReceivedEmail;
use App\Models\Contact;
use App\Models\RepairRequest;
use App\Models\VoiceCall;
use App\Support\Normalize;
use App\Voice\ApplianceType;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Phone line voice bot: Twilio sends each call here, the bot asks three questions by speech
 * and saves the answers as a repair request in the CRM inbox, like the website form does.
 */
class TwilioVoiceController extends Controller
{
    /** Questions in the order they are asked. */
    private const STEPS = [
        'name' => 'Please say your first and last name.',
        'problem' => 'What appliance needs repair, and what is wrong with it?',
        'location' => 'What city are you in?',
    ];

    private const HINTS = 'refrigerator, fridge, freezer, washer, dryer, oven, stove, range, dishwasher, microwave, range hood, '
        .'trash compactor, Moorpark, Simi Valley, Thousand Oaks, Camarillo, Newbury Park, Oxnard, Ventura, Agoura Hills';

    /** Incoming call: greet and ask the first question. */
    public function incoming(Request $request): Response
    {
        $this->remember($request, ['from' => (string) $request->input('From'), 'answers' => [], 'misses' => []]);

        return $this->twiml(
            $this->say('Thank you for calling T.S. Repair Service in Moorpark. '
                .'I will take a few details so a technician can call you back.')
            .$this->ask('name')
        );
    }

    /** Speech result for one question: keep it and ask the next one, or finish the call. */
    public function answer(Request $request, string $step): Response
    {
        $state = $this->state($request);
        $speech = Normalize::text($request->input('SpeechResult'));

        // Nothing heard: ask the same question once more, then move on without it.
        if ($speech === null && ($state['misses'][$step] ?? 0) < 1) {
            $state['misses'][$step] = 1;
            $this->remember($request, $state);

            return $this->twiml($this->say('Sorry, I did not catch that.').$this->ask($step));
        }

        $state['answers'][$step] = $speech;
        $this->remember($request, $state);

        $steps = array_keys(self::STEPS);
        $next = $steps[array_search($step, $steps, true) + 1] ?? null;
        if ($next) {
            return $this->twiml($this->ask($next));
        }

        $saved = $this->save((string) $request->input('CallSid'), $state);
        Cache::forget($this->cacheKey($request));

        return $this->twiml(
            $this->say($saved
                ? 'Thank you. We received your request and will call you back shortly at this number. '
                .'We may also text you updates about this request. Reply STOP to opt out. Goodbye.'
                : 'Sorry, we could not hear you. Please call again, or send a request on our website, T.S. repair service dot com. Goodbye.')
            .'<Hangup/>'
        );
    }

    /** Call status callback: a caller who hangs up halfway still leaves a request with what they said. */
    public function status(Request $request): Response
    {
        // Duration of every call on the number (also the AI ones), for the A/B statistics.
        if ($request->input('CallStatus') === 'completed' || $request->filled('CallDuration')) {
            VoiceCall::record((string) $request->input('CallSid'), [
                'call_status' => $request->input('CallStatus'),
                'duration_seconds' => $request->filled('CallDuration') ? (int) $request->input('CallDuration') : null,
            ]);
        }

        if (in_array($request->input('CallStatus'), ['completed', 'busy', 'no-answer', 'failed', 'canceled'], true)) {
            $state = Cache::pull($this->cacheKey($request));
            if ($state) {
                $this->save((string) $request->input('CallSid'), $state);
            }
        }

        return response()->noContent();
    }

    /** Owner pressed a key on a CRM call-back (Crm\CallController): 1 dials the customer from the business line. */
    public function connect(Request $request): Response
    {
        $customer = Cache::pull('twilio-outbound:'.$request->input('CallSid'));
        if ($request->input('Digits') !== '1' || ! $customer) {
            return $this->twiml($this->say('Call cancelled.').'<Hangup/>');
        }

        return $this->twiml('<Dial callerId="'.e(config('services.twilio.number')).'" timeout="30">'.e($customer).'</Dial>');
    }

    /** Creates the CRM request once per call; returns false when the caller said nothing. */
    private function save(string $callSid, array $state): bool
    {
        $answers = array_filter($state['answers'] ?? []);
        if (! $answers) {
            return false;
        }
        if (RepairRequest::where('call_sid', $callSid)->exists()) {
            return true;
        }

        try {
            [$repairRequest, $contact] = DB::transaction(function () use ($callSid, $state, $answers) {
                $contact = Contact::create([
                    'name' => $answers['name'] ?? 'Phone caller',
                    // Twilio sends E.164 ("+18055550123"); drop the US +1 so it formats like the website form.
                    'phone' => Normalize::phone(preg_replace('/^\+1(?=\d{10}$)/', '', $state['from'] ?? '')) ?? 'Unknown',
                    'email' => '',
                ]);
                $repairRequest = RepairRequest::create([
                    'contact_id' => $contact->id,
                    'service_id' => ApplianceType::serviceId($answers['problem'] ?? ''),
                    'location' => isset($answers['location']) ? mb_substr($answers['location'], 0, 120) : null,
                    'description' => $answers['problem'] ?? 'The caller did not describe the problem.',
                    'source' => 'phone',
                    'call_sid' => $callSid,
                ]);

                return [$repairRequest, $contact];
            });
        } catch (UniqueConstraintViolationException) {
            return true; // Saved a moment ago by the other webhook of the same call.
        }

        try {
            Mail::to(config('repair.request_recipient'))->queue(new WebFormRequestReceivedEmail($repairRequest, $contact));
        } catch (Throwable $exception) {
            report($exception);
        }

        return true;
    }

    private function ask(string $step): string
    {
        $action = e("/api/twilio/voice/answer/{$step}");

        // When nothing is said, Twilio skips to the Redirect, which posts an empty answer.
        return '<Gather input="speech" action="'.$action.'" method="POST" language="en-US" speechTimeout="auto" timeout="6"'
            .' hints="'.e(self::HINTS).'">'.$this->say(self::STEPS[$step]).'</Gather>'
            .'<Redirect method="POST">'.$action.'</Redirect>';
    }

    private function say(string $text): string
    {
        return '<Say voice="'.e(config('services.twilio.voice')).'">'.e($text).'</Say>';
    }

    private function twiml(string $verbs): Response
    {
        return response('<?xml version="1.0" encoding="UTF-8"?><Response>'.$verbs.'</Response>')
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }

    private function state(Request $request): array
    {
        return Cache::get($this->cacheKey($request))
            ?? ['from' => (string) $request->input('From'), 'answers' => [], 'misses' => []];
    }

    private function remember(Request $request, array $state): void
    {
        Cache::put($this->cacheKey($request), $state, now()->addHour());
    }

    private function cacheKey(Request $request): string
    {
        return 'twilio-call:'.$request->input('CallSid');
    }
}
