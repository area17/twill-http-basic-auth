<?php

namespace A17\TwillHttpBasicAuth\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;

class TwillHttpBasicAuthRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [];
    }

    public function rulesForUpdate(): array
    {
        return [];
    }
}
