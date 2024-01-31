# HTTP Basic Auth Twill Capsule

This Twill Capsule is intended to enable developers to configure Basic Auth on their applications.

![screenshot 1](docs/screenshot01.png)

![screenshot 2](docs/screenshot02.png)

## Domains

You can add as many domains as you need and configure different passwords for each. You can have the https://site.com, for instance, unprotected to allow public access to it, and block access to https://origin.site.com and https://admin.site.com to only allow access to people with an account, and those that have access to the HTTP Basic Auth username and password. 

## One config for all

Once you enable the `all domains (*)` entry, the same configuration will be used for all domains available, and all other domain configurations will be hidden.

## Middleware

A middleware is automatically added to all `web` routes, but you can configure this behaviour or even disable it to configure your middleware yourself:

``` php
'middleware' => [
    'automatic' => true,

    'groups' => ['web'],

    'class' => \A17\TwillHttpBasicAuth\Http\Middleware::class,
],
```

## Using authentication

If you don't want to share a single username and password with everyone that will access your pages, you can configure the package to allow existing users, both on Twill (CMS) and/or Laravel (frontend), to use their own passwords to pass Basic Auth.

## Installing

## Supported Versions
Composer will manage this automatically for you, but these are the supported versions between Twill and this package.

| Twill Version | Capsule version | Installing with Composer                             | 
|---------------|-----------------|------------------------------------------------------|
| 3.x           | 2.x             | composer require area17/twill-http-basic-auth:"^2.0" |
| 2.x           | 1.x             | composer require area17/twill-http-basic-auth:"^1.0" |

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

#### Configuring via the `.env` file

You can configure it both via CMS settings or the on `.env` file. If you set them on `.env` the CMS settings will be disabled and overloded by the `.env`.

```dotenv
TWILL_HTTP_BASIC_AUTH_ENABLED=true
TWILL_HTTP_BASIC_AUTH_USERNAME=frontend
TWILL_HTTP_BASIC_AUTH_PASSWORD=secret
```

### Database login
You can configure the package to allow users pass HTTP Auth Basic with their existing email and password, by just enabling the feature on the `.env` file:

```dotenv
TWILL_HTTP_BASIC_AUTH_TWILL_DATABASE_LOGIN_ENABLED=true
TWILL_HTTP_BASIC_AUTH_LARAVEL_DATABASE_LOGIN_ENABLED=true
```

### Rate limting

The package will also, by default, rate limit users to max of 500 requests per minute to each domain. You can configure it using this `.env` variable:

```dotenv
TWILL_HTTP_BASIC_AUTH_RATE_LIMITING_ATTEMPTS=5
```

By requiring users to have an enabled account in Twill (or Laravel) to access a protected website, this becomes an additional security feature. It also allows you to avoid disclosing the same username and password to everyone who is authorized to view the site.

## Contribute

Please contribute to this project by submitting pull requests.
