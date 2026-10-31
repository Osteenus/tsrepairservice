<?php

return [
    // Shared secret between Laravel and voice-service (Bearer token both ways).
    'api_key' => env('VOICE_API_KEY'),
    'service_url' => rtrim((string) env('VOICE_SERVICE_URL', 'http://127.0.0.1:3001'), '/'),
    // Public address Twilio reaches voice-service at (incoming calls are passed on to /voice).
    'public_url' => rtrim((string) env('VOICE_PUBLIC_URL', 'https://voice.tsrepairservice.com'), '/'),

    // Promised call-back time, and how long after it a lead counts as overdue.
    'callback_minutes' => 10,
    'overdue_minutes' => 30,

    // Claude price per million tokens (claude-haiku-5-5), for the cost column of the call statistics.
    'claude_price' => ['input' => 0.10, 'output' => 0.50],

    // Technician hours (Pacific time). Outside them the agent promises a call-back
    // "as soon as possible", and the 10 minutes start at the next opening.
    'timezone' => 'America/Los_Angeles',
    'hours' => [
        'mon' => ['08:00', '18:00'],
        'tue' => ['08:00', '18:00'],
        'wed' => ['08:00', '18:00'],
        'thu' => ['08:00', '18:00'],
        'fri' => ['08:00', '18:00'],
        'sat' => ['09:00', '15:00'],
        'sun' => null,
    ],
];
