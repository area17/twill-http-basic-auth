<?php

namespace A17\TwillHttpBasicAuth\Models;

use A17\Twill\Models\Model;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\TwillHttpBasicAuth\Services\Helpers;
use Illuminate\Database\Eloquent\Relations\HasMany;
use A17\TwillHttpBasicAuth\Models\Behaviors\Encrypt;
use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth as TwillHttpBasicAuthFacade;

/**
 * @property string|null $domain
 * @property string|null $username
 * @property string|null $password
 * @property bool|null $published
 * @property string $domain_string
 * @property string $status
 * @property string $from_dot_env
 * @property bool $configured
 * @property bool $allow_laravel_login
 * @property bool $allow_twill_login
 * @property string $allow_laravel_login_string
 * @property string $allow_twill_login_string
 */
class TwillHttpBasicAuth extends Model
{
    use HasRevisions;
    use Encrypt;

    protected $table = 'twill_basic_auth';

    protected $fillable = ['published', 'domain', 'username', 'password', 'allow_laravel_login', 'allow_twill_login'];

    protected $appends = [
        'domain_string',
        'status',
        'from_dot_env',
        'allow_laravel_login_string',
        'allow_laravel_login_string',
    ];

    public function __toString()
    {
        return ''; // Bad fix for the blank() method that calls this recursively
    }

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
        if (!$this->configured) {
            return 'disabled';
        }

        if (!$this->published) {
            return 'not published';
        }

        return 'PROTECTED';
    }

    public function getFromDotEnvAttribute(): string
    {
        return TwillHttpBasicAuthFacade::hasDotEnv() ? 'YES' : 'no';
    }

    public function getAllowLaravelLoginStringAttribute(): string
    {
        return $this->allow_laravel_login ? 'Allowed' : 'Disallowed';
    }

    public function getAllowTwillLoginStringAttribute(): string
    {
        return $this->allow_twill_login ? 'Allowed' : 'Disallowed';
    }

    public function save(array $options = []): bool
    {
        $this->attributes['published'] = $this->configured && $this->attributes['published'];

        return parent::save($options);
    }

    public function getCredentialsStringAttribute(): string
    {
        return $this->allow_twill_login ? 'Present' : 'Missing';
    }
}
