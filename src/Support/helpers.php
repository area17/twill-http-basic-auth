<?php

use A17\TwillHttpBasicAuth\Services\Helpers;
use A17\TwillHttpBasicAuth\Support\TwillHttpBasicAuth;

if (!function_exists('http_basic_auth')) {
    function http_basic_auth(): TwillHttpBasicAuth
    {
        return Helpers::httpBasicAuthInstance();
    }
}
