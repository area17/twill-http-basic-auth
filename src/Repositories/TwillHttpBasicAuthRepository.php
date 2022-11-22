<?php

namespace A17\TwillHttpBasicAuth\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\TwillHttpBasicAuth\Models\TwillHttpBasicAuth;

/**
 * @method \Illuminate\Database\Eloquent\Builder published()
 */
class TwillHttpBasicAuthRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(TwillHttpBasicAuth $model)
    {
        $this->model = $model;
    }
}
