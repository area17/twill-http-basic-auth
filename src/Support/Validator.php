<?php

namespace A17\TwillHttpBasicAuth\Support;

use Illuminate\Contracts\Validation\Rule;

class Validator implements Rule
{
    public function passes($attribute, $value): bool
    {
        return http_basic_auth()->fails();
    }

    public function message(): string
    {
        return http_basic_auth()->failedMessage();
    }
}
