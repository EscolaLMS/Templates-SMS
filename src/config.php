<?php

return [
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
        'ssl_verify' => env('TWILIO_SSL_VERIFY', true),
    ],
    'sms_driver' => env('SMS_DRIVER', 'twilio')
];
