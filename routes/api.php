<?php

use App\Http\Controllers\TwilioVoiceController;
use App\Http\Controllers\VoiceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Phone line voice bot (Twilio number webhooks). Only Twilio-signed requests get through.
Route::middleware('twilio')->prefix('twilio/voice')->controller(TwilioVoiceController::class)->group(function () {
    Route::post('/', 'incoming');
    Route::post('/answer/{step}', 'answer')->whereIn('step', ['name', 'problem', 'location']);
    Route::post('/status', 'status');
    Route::post('/connect', 'connect');
});

// AI voice agent (voice-service). Bearer VOICE_API_KEY.
Route::middleware('voice-api')->prefix('voice')->controller(VoiceApiController::class)->group(function () {
    Route::post('/leads', 'storeLead');
    Route::post('/leads/{callSid}/transfer', 'transferResult');
    Route::post('/leads/{lead}/followup', 'followupResult')->whereNumber('lead');
    Route::get('/customers', 'customer');
    Route::get('/availability', 'availability');
});
