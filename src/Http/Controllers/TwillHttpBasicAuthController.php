<?php

namespace A17\TwillHttpBasicAuth\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\TwillHttpBasicAuth\Models\TwillHttpBasicAuth;
use A17\TwillHttpBasicAuth\Repositories\TwillHttpBasicAuthRepository;
use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth as TwillHttpBasicAuthFacade;

class TwillHttpBasicAuthController extends ModuleController
{
    protected $moduleName = 'twillHttpBasicAuth';

    protected $titleColumnKey = 'domain_string';

    protected $titleFormKey = 'domain';

    protected $defaultOrders = ['domain' => 'asc'];

    protected $indexColumns = [
        'domain_string' => [
            'title' => 'Domain',
            'field' => 'domain_string',
        ],

        'status' => [
            'title' => 'Status',
            'field' => 'status',
        ],

        'username' => [
            'title' => 'Username',
            'field' => 'username',
        ],

        'allow_laravel_login' => [
            'title' => 'Laravel login',
            'field' => 'allow_laravel_login',
        ],

        'allow_twill_login' => [
            'title' => 'Twill login',
            'field' => 'allow_twill_login',
        ],

        'from_dot_env' => [
            'title' => 'From .env',
            'field' => 'from_dot_env',
        ],
    ];

    /**
     * @param int|null $parentModuleId
     * @return array|\Illuminate\Contracts\View\View|\Illuminate\View\View|RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function index($parentModuleId = null)
    {
        $this->generateDomains();

        $this->setIndexOptions();

        return parent::index($parentModuleId = null);
    }

    protected function getViewPrefix(): string|null
    {
        return 'twill-http-basic-auth::admin';
    }

    public function generateDomains(): void
    {
        if (DB::table('twill_basic_auth')->count() !== 0) {
            return;
        }

        $appDomain = TwillHttpBasicAuthFacade::getDomain(config('app.url'));

        $currentDomain = TwillHttpBasicAuthFacade::getDomain(URL::current());

        /** @phpstan-ignore-next-line  */
        app(TwillHttpBasicAuthRepository::class)->create([
            'domain' => '*',
            'published' => false,
        ]);

        if (filled($currentDomain)) {
            /** @phpstan-ignore-next-line  */
            app(TwillHttpBasicAuthRepository::class)->create([
                'domain' => $currentDomain,
                'published' => false,
            ]);
        }

        if (filled($appDomain) && $appDomain !== $currentDomain) {
            /** @phpstan-ignore-next-line  */
            app(TwillHttpBasicAuthRepository::class)->create([
                'domain' => $appDomain,
                'published' => false,
            ]);
        }
    }

    public function setIndexOptions(): void
    {
        $this->indexOptions = ['create' => !TwillHttpBasicAuthFacade::allDomainsEnabled()];
    }

    /**
     * @param array $scopes
     * @param bool $forcePagination
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getIndexItems($scopes = [], $forcePagination = false)
    {
        if (TwillHttpBasicAuthFacade::allDomainsEnabled()) {
            $scopes['domain'] = '*';
        }

        return parent::getIndexItems($scopes, $forcePagination);
    }
}
