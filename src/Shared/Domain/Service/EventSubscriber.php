<?php

namespace Shared\Domain\Service;

use Shared\Domain\Model\DomainEvent;

interface EventSubscriber
{
    public function isSubscribedTo(DomainEvent $event): bool;
    public function handle(DomainEvent $event): void;
}
