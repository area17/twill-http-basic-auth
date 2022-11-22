<?php

namespace A17\TwillHttpBasicAuth\Http;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use A17\HttpBasicAuth\HttpBasicAuth;
use Illuminate\Http\RedirectResponse;
use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth;

class Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = TwillHttpBasicAuth::middleware($request);

        if ($response !== null) {
            return $response;
        }

        return $next($request);
    }
}
