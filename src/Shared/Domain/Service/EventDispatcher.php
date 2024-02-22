<?php

namespace Shared\Domain\Service;

use Shared\Domain\Model\DomainEvent;

abstract class EventDispatcher
{
    public abstract function execute(DomainEvent $domainEvent): void;
}
