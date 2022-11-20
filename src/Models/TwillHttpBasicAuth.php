<?php

namespace A17\TwillHttpBasicAuth\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\TwillHttpBasicAuth\Services\Helpers;
use Illuminate\Database\Eloquent\Relations\HasMany;
use A17\TwillHttpBasicAuth\Models\Behaviors\Encrypt;
use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth as TwillHttpBasicAuthFacade;

class TwillHttpBasicAuth extends Model
{
    use HasRevisions;
    use Encrypt;

    protected $table = 'twill_basic_auth';

    protected $fillable = ['published', 'domain', 'username', 'password', 'allow_laravel_login', 'allow_twill_login'];

    protected $appends = ['domain_string', 'status', 'from_dot_env'];

    public function getUsernameAttribute(): string|null
    {
        return $this->decrypt(
            Helpers::instance()
                ->setCurrent($this)
                ->username(true),
        );
    }

    public function setUsernameAttribute(string|null $value): void
    {
        $this->attributes['username'] = $this->encrypt($value);
    }

    public function getPasswordAttribute(): string|null
    {
        return $this->decrypt(
            Helpers::instance()
                ->setCurrent($this)
                ->password(true),
        );
    }

    public function setPasswordAttribute(string|null $value): void
    {
        $this->attributes['password'] = $this->encrypt($value);
    }

    public function getPublishedAttribute(): string|null
    {
        return Helpers::instance()
            ->setCurrent($this)
            ->published(true);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany($this->getRevisionModel(), 'twill_basic_auth_id')->orderBy('created_at', 'desc');
    }

    public function getDomainStringAttribute(): string|null
    {
        $domain = $this->domain;

        if ($domain === '*') {
            return '* (all domains)';
        }

        return $domain;
    }

    public function getConfiguredAttribute(): bool
    {
        return filled($this->username) && filled($this->password);
    }

    public function getStatusAttribute(): string
    {
        return $this->published && $this->configured ? 'protected' : 'unprotected';
    }

    public function getFromDotEnvAttribute(): string
    {
        return TwillHttpBasicAuthFacade::hasDotEnv() ? 'yes' : 'no';
    }
}
