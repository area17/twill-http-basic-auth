<?php

namespace A17\TwillHttpBasicAuth\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\TwillHttpBasicAuth\Models\TwillHttpBasicAuth;

class TwillHttpBasicAuthRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(TwillHttpBasicAuth $model)
    {
        $this->model = $model;
    }

    public function theOnlyOne(): TwillHttpBasicAuth
    {
        $record = TwillHttpBasicAuth::query()
            ->published()
            ->orderBy('id')
            ->first();

        return $record ?? $this->generate();
    }

    private function generate(): TwillHttpBasicAuth
    {
        return app(self::class)->create([
            'site_key' => null,

            'private_key' => null,

            'published' => true,
        ]);
    }
}
