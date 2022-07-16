<?php

namespace AuthMicroservice\Shared\Infrastructure\Laravel\Domain\Service;

use AuthMicroservice\Shared\Domain\Service\DomainEvent;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;

class EventDispatcherLaravel extends EventDispatcher
{
    public function execute(DomainEvent $domainEvent): void
    {
        event($domainEvent);
    }
}
