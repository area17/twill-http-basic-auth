<?php

namespace A17\TwillHttpBasicAuth;

use Illuminate\Support\Str;
use A17\Twill\Facades\TwillCapsules;
use Illuminate\Contracts\Http\Kernel;
use A17\Twill\TwillPackageServiceProvider;
use A17\TwillHttpBasicAuth\Http\Middleware;
use A17\TwillHttpBasicAuth\Services\Helpers;
use A17\TwillHttpBasicAuth\Support\TwillHttpBasicAuth;

class ServiceProvider extends TwillPackageServiceProvider
{
    /** @var bool $autoRegisterCapsules */
    protected $autoRegisterCapsules = false;

    public function boot(): void
    {
        $this->registerThisCapsule();

        $this->registerViews();

        $this->registerConfig();

        $this->configureMiddeleware();

        parent::boot();
    }

    protected function registerThisCapsule(): void
    {
        $namespace = $this->getCapsuleNamespace();

        TwillCapsules::registerPackageCapsule(
            Str::afterLast($namespace, '\\'),
            $namespace,
            $this->getPackageDirectory() . '/src',
        );

        app()->singleton(TwillHttpBasicAuth::class, fn() => new TwillHttpBasicAuth());
    }

    public function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'twill-http-basic-auth');
    }

    public function registerConfig(): void
    {
        $package = 'twill-http-basic-auth';

        $path = __DIR__ . "/config/{$package}.php";

        $this->mergeConfigFrom($path, $package);

        $this->publishes([
            $path => config_path("{$package}.php"),
        ]);
    }

    public function configureMiddeleware(): void
    {
        if (config('twill-http-basic-auth.middleware.automatic')) {
            /**
             * @phpstan-ignore-next-line
             * @var \Illuminate\Foundation\Http\Kernel $kernel
             */
            $kernel = $this->app[Kernel::class];

            foreach (config('twill-http-basic-auth.middleware.groups', []) as $group) {
                $kernel->appendMiddlewareToGroup($group, config('twill-http-basic-auth.middleware.class'));
            }
        }
    }
}
