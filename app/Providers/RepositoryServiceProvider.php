<?php

namespace App\Providers;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
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
            // This is what Doctrine's EntityRepository needs in its constructor.
            return new UserDoctrineRepository(
                $app['em'],
                $app['em']->getClassMetaData(User::class)
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
