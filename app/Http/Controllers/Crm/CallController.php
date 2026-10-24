<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Support\Normalize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Call a customer from the business line: Twilio rings the owner's mobile first and, after
 * "press 1" (so a voicemail pickup never reaches the customer), dials the customer with
 * the business number as caller ID. See TwilioVoiceController::connect().
 */
class CallController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:50'], 'name' => ['nullable', 'string', 'max:255']]);
        $config = config('services.twilio');

        if (! $config['account_sid'] || ! $config['auth_token'] || ! $config['owner_phone']) {
            return $this->failed('Business line calls are not set up (TWILIO_ACCOUNT_SID, TWILIO_OWNER_PHONE).');
        }
        $customer = Normalize::e164($data['phone']);
        if (! $customer) {
            return $this->failed('This phone number cannot be dialed from the business line. Use the regular Call button.');
        }

        $who = Normalize::text($data['name'] ?? null) ?? 'the customer';
        $voice = e($config['voice']);
        $twiml = '<Response><Gather numDigits="1" timeout="8" method="POST" action="'.e(url('/api/twilio/voice/connect')).'">'
            .'<Say voice="'.$voice.'">'.e("Business line call to {$who}. Press 1 to connect.").'</Say></Gather>'
            .'<Say voice="'.$voice.'">Call cancelled.</Say></Response>';

        try {
            $response = Http::asForm()->timeout(15)
                ->withBasicAuth($config['account_sid'], $config['auth_token'])
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$config['account_sid']}/Calls.json", [
                    'To' => $config['owner_phone'],
                    'From' => $config['number'],
                    'Twiml' => $twiml,
                    'Timeout' => 25,
                ])->throw();
        } catch (Throwable $exception) {
            report($exception);

            return $this->failed('Twilio could not start the call. Try again or use the regular Call button.');
        }

        // The "press 1" webhook looks up whom to dial by the call it belongs to.
        Cache::put('twilio-outbound:'.$response->json('sid'), $customer, now()->addMinutes(10));

        return back()->with('flash.banner', 'Calling your phone now. Answer and press 1 to connect.');
    }

    private function failed(string $message): RedirectResponse
    {
        return back()->with('flash.banner', $message)->with('flash.bannerStyle', 'danger');
    }
}
