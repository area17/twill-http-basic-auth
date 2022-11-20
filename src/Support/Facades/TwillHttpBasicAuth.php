<?php

namespace A17\TwillHttpBasicAuth\Support\Facades;

use Illuminate\Support\Facades\Facade;
use A17\TwillHttpBasicAuth\Support\TwillHttpBasicAuth as TwillHttpBasicAuthService;

class TwillHttpBasicAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwillHttpBasicAuthService::class;
    }
}
