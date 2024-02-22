<?php

namespace Shared\Infrastructure\Laravel\Domain\Service;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\EventSubscriber;

class PersistDomainEventSubscriber implements EventSubscriber
{
    public function isSubscribedTo(DomainEvent $event): bool
    {
        return true;
    }

    public function handle(DomainEvent $event): void
    {
        DB::table('event')->insert([
            'id' => Uuid::uuid4(),
            'event_name' => class_basename($event),
            'event_data' => json_encode($event),
            'occurred_on' => $event->occurredOn(),
        ]);
    }
}
