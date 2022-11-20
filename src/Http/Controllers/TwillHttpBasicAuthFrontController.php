<?php

namespace A17\TwillHttpBasicAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use A17\TwillHttpBasicAuth\Services\Helpers;
use A17\TwillHttpBasicAuth\Support\Validator as TwillHttpBasicAuthValidator;

class TwillHttpBasicAuthFrontController
{
    public function show(): View|Factory
    {
        /** @var view-string $view */
        $view = 'http-basic-auth::front.form';

        return view($view);
    }

    public function store(Request $request): array
    {
        $request->validate([
            'g-recaptcha-response' => ['required', 'string', new TwillHttpBasicAuthValidator()],
        ]);

        $response = Helpers::instance()->verify($request->get('g-recaptcha-response'));

        if (empty($response)) {
            return [
                'success' => false,
                'message' => 'HTTP Basic Auth service error',
            ];
        }

        return $response->json();
    }
}
