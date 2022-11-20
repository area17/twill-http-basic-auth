<?php

namespace A17\TwillHttpBasicAuth\Support;

use Illuminate\Support\Arr;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use A17\TwillHttpBasicAuth\Repositories\TwillHttpBasicAuthRepository;
use A17\TwillHttpBasicAuth\Models\TwillHttpBasicAuth as TwillHttpBasicAuthModel;

class TwillHttpBasicAuth
{
    public const DEFAULT_ERROR_MESSAGE = 'Invisible captcha failed.';

    protected array|null $config = null;

    protected bool|null $isConfigured = null;

    protected bool|null $enabled = null;

    protected Response|null $http_basic_authResponse = null;

    protected TwillHttpBasicAuthModel|null $current = null;

    public function __construct()
    {
        $this->setConfigured();

        $this->setEnabled();

        $this->configureViews();
    }

    public function config(string|null $key = null): mixed
    {
        $this->config ??= filled($this->config) ? $this->config : (array) config('twill-http-basic-auth');

        if (blank($key)) {
            return $this->config;
        }

        return Arr::get((array) $this->config, $key);
    }

    public function enabled(): bool
    {
        return $this->enabled ??
            $this->isConfigured() &&
                ($this->hasDotEnv() ? $this->config('enabled') ?? false : $this->readFromDatabase('published'));
    }

    public function password(bool $force = false): string|null
    {
        return $this->get('keys.password', 'password', $force);
    }

    public function username(bool $force = false): string|null
    {
        return $this->get('keys.username', 'username', $force);
    }

    public function published(bool $force = false): string|null
    {
        return $this->get('enabled', 'published', $force);
    }

    public function get(string $configKey, string $databaseColumn, bool $force = false): string|null
    {
        if (!$force && (!$this->isConfigured() || !$this->enabled())) {
            return null;
        }

        return $this->hasDotEnv() ? $this->config($configKey) : $this->readFromDatabase($databaseColumn);
    }

    protected function readFromDatabase(string $key): string|bool|null
    {
        if (blank($this->current)) {
            $domains ??= app(TwillHttpBasicAuthRepository::class)
                ->published()
                ->whereNotNull('username')
                ->whereNotNull('password')
                ->orderBy('domain')
                ->get();

            if ($domains->isEmpty()) {
                return null;
            }

            if ($domains->first()->domain === '*') {
                $this->current = $domains->first();
            } else {
                $this->current = $domains->firstWhere('domain', $this->getDomain());
            }
        }

        if (blank($this->current)) {
            return null;
        }

        return $this->current->getAttributes()[$key];
    }

    public function hasDotEnv(): bool
    {
        return filled($this->config('keys.username') ?? null) || filled($this->config('keys.password') ?? null);
    }

    protected function isConfigured(): bool
    {
        return $this->isConfigured ??
            $this->hasDotEnv() || (filled($this->username(true)) && filled($this->password(true)));
    }

    protected function setConfigured(): void
    {
        $this->isConfigured = $this->isConfigured();
    }

    protected function setEnabled(): void
    {
        $this->enabled = $this->enabled();
    }

    protected function configureViews(): void
    {
        View::addNamespace('http-basic-auth', __DIR__ . '/../resources/views');
    }

    public function passes(string|null $responseToken = null): bool
    {
        if (!$this->enabled()) {
            return true; // TODO: Should this be false?
        }

        $response = $this->verify($responseToken)?->json();

        if (blank($response)) {
            return false;
        }

        return $response['success'] ?? false;
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function failedMessage(): string
    {
        $message = __($key = $this->config('validation.lang_key'));

        if ($message !== $key) {
            return $message;
        }

        return $this->config('validation.failed') ?? self::DEFAULT_ERROR_MESSAGE;
    }

    public function getDomain(string|null $url = null): string|null
    {
        $url = parse_url($url ?? request()->url());

        return $url['host'] ?? null;
    }

    public function setCurrent(TwillHttpBasicAuthModel $current): static
    {
        $this->current = $current;

        return $this;
    }

    public function allDomainsEnabled(): bool
    {
        return $this->hasDotEnv() || $this->readFromDatabase('domain') === '*';
    }
}
