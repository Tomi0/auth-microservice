<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\EventSubscriber;
use Shared\Infrastructure\Laravel\Domain\Service\PersistDomainEventSubscriber;

class EventServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        if (! env('IN_MEMORY_REPOSITORY')) {
            EventPublisher::instance()->subscribe(
                new PersistDomainEventSubscriber()
            );
        }
    }
}
