<?php

namespace App\Providers;

use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\EncodePassword;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Authentication\Infrastructure\Domain\Service\User\CheckPasswordHashLaravel;
use Authentication\Infrastructure\Domain\Service\User\EncodePasswordLaravel;
use Authentication\Infrastructure\Domain\Service\User\GenerateJwtTokenLcobucciJwt;
use Illuminate\Support\ServiceProvider;
use Shared\Domain\Service\GetConfigItem;
use Shared\Domain\Service\RandomStringGenerator;
use Shared\Infrastructure\Laravel\Domain\Service\GetConfigItemLaravel;
use Shared\Infrastructure\Laravel\Domain\Service\RandomStringGeneratorLaravel;

class BindServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GenerateJwtToken::class, GenerateJwtTokenLcobucciJwt::class);
        $this->app->bind(EncodePassword::class, EncodePasswordLaravel::class);
        $this->app->bind(CheckPasswordHash::class, CheckPasswordHashLaravel::class);
        $this->app->bind(RandomStringGenerator::class, RandomStringGeneratorLaravel::class);
        $this->app->bind(GetConfigItem::class, GetConfigItemLaravel::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
