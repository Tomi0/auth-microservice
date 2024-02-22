<?php

namespace Shared\Infrastructure\Laravel\Domain\Service;

use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\EventDispatcher;

class EventDispatcherLaravel extends EventDispatcher
{
    public function execute(DomainEvent $domainEvent): void
    {
        event($domainEvent);
    }
}
