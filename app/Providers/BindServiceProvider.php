<?php

namespace App\Providers;

use AuthMicroservice\Authentication\Domain\Service\User\CheckPasswordHash;
use AuthMicroservice\Authentication\Domain\Service\User\EncodePassword;
use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;
use AuthMicroservice\Authentication\Infrastructure\Domain\Service\User\CheckPasswordHashLaravel;
use AuthMicroservice\Authentication\Infrastructure\Domain\Service\User\EncodePasswordLaravel;
use AuthMicroservice\Authentication\Infrastructure\Domain\Service\User\GenerateJwtTokenLcobucciJwt;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;
use AuthMicroservice\Shared\Infrastructure\Laravel\Domain\Service\EventDispatcherLaravel;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(EventDispatcher::class, EventDispatcherLaravel::class);
        $this->app->bind(CheckPasswordHash::class, CheckPasswordHashLaravel::class);
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
