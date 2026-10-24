<?php

use App\Http\Controllers\TwilioVoiceController;
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
});
