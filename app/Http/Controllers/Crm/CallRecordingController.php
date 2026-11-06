<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Voice\TwilioApi;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

/** Plays a call recording in the CRM. The audio comes through Laravel; the browser never sees a Twilio URL. */
class CallRecordingController extends Controller
{
    public function show(Request $request, Call $call, TwilioApi $twilio): StreamedResponse
    {
        abort_unless($call->recording_sid, 404);

        try {
            $audio = $twilio->recordingAudio($call->recording_sid, $request->header('Range'));
        } catch (Throwable $exception) {
            report($exception);
            abort(502, 'The recording could not be loaded from Twilio.');
        }
        abort_unless(in_array($audio->status(), [200, 206], true), $audio->status() === 416 ? 416 : 404);

        $body = $audio->toPsrResponse()->getBody();

        return response()->stream(function () use ($body) {
            while (! $body->eof()) {
                echo $body->read(64 * 1024);
                flush();
            }
        }, $audio->status(), array_filter([
            'Content-Type' => 'audio/mpeg',
            'Content-Length' => $audio->header('Content-Length') ?: null,
            'Content-Range' => $audio->header('Content-Range') ?: null,
            'Accept-Ranges' => 'bytes',
        ]));
    }
}
