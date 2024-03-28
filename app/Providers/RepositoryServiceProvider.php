<?php

namespace App\Providers;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostRepository;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Infrastructure\Doctrine\Domain\Model\AutorizedHost\AuthorizedHostDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\SigningKey\SigningKeyDoctrineRepository;
use Illuminate\Support\ServiceProvider;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword\TokenResetPasswordDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\User\UserDoctrineRepository;

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
        $this->app->bind(AuthorizedHostRepository::class, function($app) {
            return new AuthorizedHostDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(AuthorizedHost::class)
            );
        });
        $this->app->bind(SigningKeyRepository::class, function($app) {
            return new SigningKeyDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(SigningKey::class)
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
