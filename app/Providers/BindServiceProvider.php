<?php

namespace App\Providers;

use AuthMicroservice\Authentication\Domain\Service\User\CheckEmailAndPasswordMatch;
use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;
use AuthMicroservice\Authentication\Infrastructure\Domain\Service\User\CheckEmailAndPasswordMatchLaravel;
use AuthMicroservice\Authentication\Infrastructure\Domain\Service\User\GenerateJwtTokenLcobucciJwt;
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
        $this->app->bind(CheckEmailAndPasswordMatch::class, CheckEmailAndPasswordMatchLaravel::class);
        $this->app->bind(GenerateJwtToken::class, GenerateJwtTokenLcobucciJwt::class);
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
