<?php

namespace A17\TwillHttpBasicAuth\Http;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use A17\HttpBasicAuth\HttpBasicAuth;
use Illuminate\Http\RedirectResponse;

class Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = HttpBasicAuth::checkAuth($request);

        if ($response !== null) {
            return $response;
        }

        return $next($request);
    }
}
