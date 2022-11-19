<?php

return [
    'enabled' => env('TWILL_HTTP_BASIC_AUTH_ENABLED', false),

    'validation' => [
        'lang_key' => 'validation.http_basic_auth',
        'failed' => \A17\TwillHttpBasicAuth\Support\TwillHttpBasicAuth::DEFAULT_ERROR_MESSAGE,
    ],

    'keys' => [
        'site' => env('TWILL_HTTP_BASIC_AUTH_SITE_KEY'),
        'private' => env('TWILL_HTTP_BASIC_AUTH_PRIVATE_KEY'),
    ],
];
