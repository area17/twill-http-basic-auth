<?php

namespace A17\TwillHttpBasicAuth\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use A17\Twill\Models\Behaviors\HasRevisions;
use Illuminate\Database\Eloquent\Relations\HasMany;
use A17\TwillHttpBasicAuth\Models\Behaviors\Encrypt;

class TwillHttpBasicAuth extends Model
{
    use HasRevisions;
    use Encrypt;

    protected $table = 'twill_ggl_captcha';

    protected $fillable = ['published', 'site_key', 'private_key'];

    public function getSiteKeyAttribute(): string|null
    {
        return $this->decrypt(http_basic_auth()->siteKey(true));
    }

    public function setSiteKeyAttribute(string|null $value): void
    {
        $this->attributes['site_key'] = $this->encrypt($value);
    }

    public function getPrivateKeyAttribute(): string|null
    {
        return $this->decrypt(http_basic_auth()->privateKey(true));
    }

    public function setPrivateKeyAttribute(string|null $value): void
    {
        $this->attributes['private_key'] = $this->encrypt($value);
    }

    public function getPublishedAttribute(): string|null
    {
        return http_basic_auth()->published(true);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany($this->getRevisionModel(), 'twill_grecaptcha_id')->orderBy('created_at', 'desc');
    }
}
