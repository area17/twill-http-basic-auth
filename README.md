# HTTP Basic Auth Twill Capsule

## Installing

### Require the Composer package:

``` bash
composer require area17/twill-http-basic-auth
```

### Publish the configuration

``` bash
php artisan vendor:publish --provider="A17\TwillHttpBasicAuth\ServiceProvider"
```

### Load Capsule helpers by adding calling the loader to your AppServiceProvider:

``` php
/**
 * Register any application services.
 *
 * @return void
 */
public function register()
{
    \A17\TwillHttpBasicAuth\Services\Helpers::load();
}
```

#### Create debugging routes to check if it's all good

```php
Route::prefix('/debug')->group(function () {
    Route::get('/http-basic-auth', [A17\TwillHttpBasicAuth\Http\Controllers\TwillHttpBasicAuthFrontController::class, 'show'])->name(
        'http-basic-auth.show',
    );

    Route::post('/http-basic-auth', [A17\TwillHttpBasicAuth\Http\Controllers\TwillHttpBasicAuthFrontController::class, 'store'])->name(
        'http-basic-auth.store',
    );
});
```

#### Translate validation messages on validation.php

```php
'http_basic_auth' => 'Failed invisible Google reCAPTCHA, please try again.',
```

#### Sharing it in your views

To have a `$TwillHttpBasicAuth` shared on your views, you can call this helper on your `AppServiceProvider`: 

``` php
\A17\TwillHttpBasicAuth\Services\Helpers::viewShare()
```

#### Test it out

Head to: http://site.com/debug/http-basic-auth

#### Captcha keys works both on .env or in the CMS settings, but .env trumps the CMS settings

```dotenv
TWILL_GOOGLE_RECAPTCHA_SITE_KEY=61df2g3hjkj7hgf6df54g3hj2kl3k4j5h6G
TWILL_GOOGLE_RECAPTCHA_PRIVATE_KEY=6Lg5h43jkl45k6jh7g6h5j4kl3nj5k4l3P
TWILL_GOOGLE_RECAPTCHA_ENABLED=true
```

#### Check the working form example

File: app/Twill/Capsules/GoogleRecaptchas/resources/views/front/form.blade.php

#### Use the validator

```php
use A17\TwillHttpBasicAuth\Support\Validator as GoogleRecaptchaValidator;

$request->validate([
    'g-recaptcha-response' => ['required', 'string', new GoogleRecaptchaValidator()],
]);
```

## Contribute

Please contribute to this project by submitting pull requests.
