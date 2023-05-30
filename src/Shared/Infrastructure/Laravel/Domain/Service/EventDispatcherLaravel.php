<?php

namespace Shared\Infrastructure\Laravel\Domain\Service;

use Shared\Domain\Service\DomainEvent;
use Shared\Domain\Service\EventDispatcher;

class EventDispatcherLaravel extends EventDispatcher
{
    public function execute(DomainEvent $domainEvent): void
    {
        event($domainEvent);
    }
}
