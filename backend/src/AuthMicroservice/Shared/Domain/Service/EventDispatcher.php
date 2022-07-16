<?php

namespace AuthMicroservice\Shared\Domain\Service;

abstract class EventDispatcher
{
    public abstract function execute(DomainEvent $domainEvent): void;
}
