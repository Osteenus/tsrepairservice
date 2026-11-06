<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        // Business line (caller ID for CRM call-backs) and the owner's mobile that Twilio rings first, both E.164.
        // Either variable name works (TWILIO_PHONE_NUMBER / TWILIO_NUMBER, OWNER_PHONE / TWILIO_OWNER_PHONE).
        'number' => env('TWILIO_PHONE_NUMBER', env('TWILIO_NUMBER', '+18053348375')),
        'owner_phone' => env('OWNER_PHONE', env('TWILIO_OWNER_PHONE')),
        // The owner's own phones used for test calls: marked "Test", never counted or blocked. Comma-separated E.164.
        'test_numbers' => array_values(array_filter(array_map('trim', explode(',', (string) env('TWILIO_TEST_NUMBERS', '+18059912874'))))),
        // Voice Intelligence service that transcribes recordings on demand (Twilio Console → Conversational Intelligence).
        'intelligence_service_sid' => env('TWILIO_INTELLIGENCE_SERVICE_SID'),
        'voice' => env('TWILIO_VOICE', 'Google.en-US-Chirp3-HD-Aoede'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
