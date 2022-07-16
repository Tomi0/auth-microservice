<?php

namespace App\Providers;

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword\TokenResetPasswordDoctrineRepository;
use AuthMicroservice\Authentication\Infrastructure\Doctrine\Domain\Model\User\UserDoctrineRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function($app) {
            return new UserDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(User::class)
            );
        });
        $this->app->bind(TokenResetPasswordRepository::class, function($app) {
            return new TokenResetPasswordDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(TokenResetPassword::class)
            );
        });
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
