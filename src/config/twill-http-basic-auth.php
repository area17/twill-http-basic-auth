<?php

return [
    'enabled' => env('TWILL_HTTP_BASIC_AUTH_ENABLED', false),

    'keys' => [
        'username' => env('TWILL_HTTP_BASIC_AUTH_USERNAME'),
        'password' => env('TWILL_HTTP_BASIC_AUTH_PASSWORD'),
    ],
];
