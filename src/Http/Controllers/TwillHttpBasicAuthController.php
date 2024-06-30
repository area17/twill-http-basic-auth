<?php

namespace A17\TwillHttpBasicAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\TwillHttpBasicAuth\Repositories\TwillHttpBasicAuthRepository;
use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth as TwillHttpBasicAuthFacade;

class TwillHttpBasicAuthController extends ModuleController
{
    protected $moduleName = 'twillHttpBasicAuths';

    protected $titleColumnKey = 'domain_string';

    protected $titleFormKey = 'domain';

    protected $defaultOrders = ['domain' => 'asc'];

    public function index(int|null $parentModuleId = null): mixed
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

    public function publish(): JsonResponse
    {
        $all = $this->request->all();

        $all['active'] = $all['active'] ?? false;

        $this->request->merge($all);

        return parent::publish();
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->push(
            Text::make()
                ->field('status')
                ->title('Status'),
        );

        $table->push(
            Text::make()
                ->field('from_dot_env')
                ->title('From .env'),
        );

        $table->push(
            Text::make()
                ->field('credentials_string')
                ->title('Credentials'),
        );

        $table->push(
            Text::make()
                ->field('username')
                ->title('Username'),
        );

        $table->push(
            Text::make()
                ->field('allow_laravel_login_string')
                ->title('Laravel login'),
        );

        $table->push(
            Text::make()
                ->field('allow_twill_login_string')
                ->title('Twill login'),
        );

        return $table;
    }
}
