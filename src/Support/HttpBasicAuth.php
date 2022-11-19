<?php

namespace A17\TwillHttpBasicAuth\Support;

use Illuminate\Support\Arr;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use A17\TwillHttpBasicAuth\Repositories\TwillHttpBasicAuthRepository;
use A17\TwillHttpBasicAuth\Models\TwillHttpBasicAuth as TwillHttpBasicAuthModel;

class HttpBasicAuth
{
    public const DEFAULT_ERROR_MESSAGE = 'Invisible captcha failed.';

    protected array|null $config = null;

    protected bool|null $isConfigured = null;

    protected bool|null $enabled = null;

    protected Response|null $http_basic_authResponse = null;

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
                ($this->hasConfig() ? $this->config('enabled') ?? false : $this->readFromDatabase('published'));
    }

    public function privateKey(bool $force = false): string|null
    {
        return $this->get('keys.private', 'private_key', $force);
    }

    public function siteKey(bool $force = false): string|null
    {
        return $this->get('keys.site', 'site_key', $force);
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

        return $this->hasConfig() ? $this->config($configKey) : $this->readFromDatabase($databaseColumn);
    }

    private function readFromDatabase(string $string): string|bool|null
    {
        $httpBasicAuth = app(TwillHttpBasicAuthRepository::class)->theOnlyOne();

        return $httpBasicAuth->getAttributes()[$string] ?? null;
    }

    public function hasConfig(): bool
    {
        return filled($this->config('keys.site') ?? null) || filled($this->config('keys.private') ?? null);
    }

    public function asset(): string|null
    {
        if (!$this->enabled()) {
            return null;
        }

        return 'https://www.google.com/recaptcha/api.js?render=' . $this->siteKey();
    }

    private function isConfigured(): bool
    {
        return $this->isConfigured ?? filled($this->siteKey(true)) && filled($this->privateKey(true));
    }

    private function setConfigured(): void
    {
        $this->isConfigured = $this->isConfigured();
    }

    private function setEnabled(): void
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

    public function verify(string|null $responseToken): Response|null
    {
        if (blank($responseToken = $this->responseToken($responseToken))) {
            return null;
        }

        return $this->googleResponse ??= Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $this->privateKey(),
            'response' => $responseToken,
        ]);
    }

    public function responseToken(string|null $responseToken = null): string|null
    {
        return $responseToken ?? request()->input('g-recaptcha-response');
    }

    public function failedMessage(): string
    {
        $message = __($key = $this->config('validation.lang_key'));

        if ($message !== $key) {
            return $message;
        }

        return $this->config('validation.failed') ?? self::DEFAULT_ERROR_MESSAGE;
    }
}
