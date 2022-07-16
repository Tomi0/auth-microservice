<?php

namespace App\Providers;

use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\Factories\\' . class_basename($modelName) . 'Factory';
        });

        $this->app->singleton(Configuration::class, function () {
            $configuration =  Configuration::forSymmetricSigner(
                new Sha256(),
                InMemory::base64Encoded(config('jwt.jwt_token'))
            );
            $configuration->setValidationConstraints(
                new SignedWith($configuration->signer(), $configuration->signingKey()),
                new StrictValidAt(new SystemClock(new DateTimeZone('Europe/Madrid')))
            );
            return $configuration;
        });
    }
}
