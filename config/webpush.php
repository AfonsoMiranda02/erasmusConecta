<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VAPID Keys
    |--------------------------------------------------------------------------
    |
    | VAPID (Voluntary Application Server Identification) keys are used for
    | Web Push Notifications. You can generate these keys using:
    | php artisan webpush:vapid
    |
    | Or use an online tool like:
    | https://web-push-codelab.glitch.me/
    |
    */

    'vapid' => [
        'public_key' => env('VAPID_PUBLIC_KEY', ''),
        'private_key' => env('VAPID_PRIVATE_KEY', ''),
        'subject' => env('VAPID_SUBJECT', env('APP_URL')),
    ],

    /*
    |--------------------------------------------------------------------------
    | GCM API Key (Optional)
    |--------------------------------------------------------------------------
    |
    | Google Cloud Messaging API key for Firebase Cloud Messaging.
    | Only needed if you want to support older Android devices.
    |
    */

    'gcm' => [
        'key' => env('GCM_KEY', ''),
    ],
];

