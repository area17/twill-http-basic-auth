<?php

namespace A17\TwillHttpBasicAuth\Services;

use A17\TwillHttpBasicAuth\Support\TwillHttpBasicAuth;

class Helpers
{
    public static function load(): void
    {
        require __DIR__ . '/../Support/helpers.php';
    }

    public static function httpBasicAuthInstance(): TwillHttpBasicAuth
    {
        if (!app()->bound('http-basic-auth')) {
            app()->singleton('http-basic-auth', fn() => new TwillHttpBasicAuth());
        }

        return app('http-basic-auth');
    }

    public static function viewShare(): void
    {
        $httpBasicAuth = Helpers::httpBasicAuthInstance();

        view()->share('TwillHttpBasicAuth', $httpBasicAuth->config() + ['asset' => $httpBasicAuth->asset()]);
    }
}
