<?php

namespace App\Providers;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\AuthorizationCode\AuthorizationCodeDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\AutorizedHost\ClientDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\SigningKey\SigningKeyDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword\TokenResetPasswordDoctrineRepository;
use Authentication\Infrastructure\Doctrine\Domain\Model\User\UserDoctrineRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\AuthorizationCode\AuthorizationCodeInMemoryRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\Client\ClientInMemoryRepository;
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
        $this->app->bind(ClientRepository::class, function ($app) {
            return new ClientDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(Client::class)
            );
        });
        $this->app->bind(SigningKeyRepository::class, function ($app) {
            return new SigningKeyDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(SigningKey::class)
            );
        });
        $this->app->bind(AuthorizationCodeRepository::class, function ($app) {
            return new AuthorizationCodeDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(AuthorizationCode::class)
            );
        });
    }

    private function inMemoryRepository(): void
    {
        $this->app->bind(UserRepository::class, UserInMemoryRepository::class);
        $this->app->bind(TokenResetPasswordRepository::class, TokenResetPasswordInMemoryRepository::class);
        $this->app->bind(ClientRepository::class, ClientInMemoryRepository::class);
        $this->app->bind(SigningKeyRepository::class, SigningKeyInMemoryRepository::class);
        $this->app->bind(AuthorizationCodeRepository::class, AuthorizationCodeInMemoryRepository::class);
    }
}
