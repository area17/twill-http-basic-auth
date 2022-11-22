<?php

namespace A17\TwillHttpBasicAuth\Services;

use A17\TwillHttpBasicAuth\Support\TwillHttpBasicAuth;

class Helpers
{
    public static function load(): void
    {
        require __DIR__ . '/../Support/helpers.php';
    }

    public static function instance(): TwillHttpBasicAuth
    {
        return app(TwillHttpBasicAuth::class);
    }
}
