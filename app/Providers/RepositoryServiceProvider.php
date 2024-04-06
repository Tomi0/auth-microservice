<?php

namespace App\Providers;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\AutorizedHost\AuthorizedHostDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\SigningKey\SigningKeyDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword\TokenResetPasswordDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\User\UserDoctrineRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\AuthorizedHost\AuthorizedHostInMemoryRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\SigningKey\SigningKeyInMemoryRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\TokenResetPassword\TokenResetPasswordInMemoryRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\User\UserInMemoryRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (env('IN_MEMORY_REPOSITORY')) {
            $this->inMemoryRepository();
        } else {
            $this->doctrineRepository();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * @return void
     */
    public function doctrineRepository(): void
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(User::class)
            );
        });
        $this->app->bind(TokenResetPasswordRepository::class, function ($app) {
            return new TokenResetPasswordDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(TokenResetPassword::class)
            );
        });
        $this->app->bind(AuthorizedHostRepository::class, function ($app) {
            return new AuthorizedHostDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(AuthorizedHost::class)
            );
        });
        $this->app->bind(SigningKeyRepository::class, function ($app) {
            return new SigningKeyDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(SigningKey::class)
            );
        });
    }

    private function inMemoryRepository(): void
    {
        $this->app->bind(UserRepository::class, UserInMemoryRepository::class);
        $this->app->bind(TokenResetPasswordRepository::class, TokenResetPasswordInMemoryRepository::class);
        $this->app->bind(AuthorizedHostRepository::class, AuthorizedHostInMemoryRepository::class);
        $this->app->bind(SigningKeyRepository::class, SigningKeyInMemoryRepository::class);
    }
}
