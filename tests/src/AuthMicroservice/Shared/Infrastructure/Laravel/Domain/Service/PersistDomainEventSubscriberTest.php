<?php

namespace Tests\src\AuthMicroservice\Shared\Infrastructure\Laravel\Domain\Service;

use Illuminate\Support\Facades\DB;
use Shared\Domain\Model\DomainEvent;
use Shared\Infrastructure\Laravel\Domain\Service\PersistDomainEventSubscriber;
use Tests\TestCase;

class PersistDomainEventSubscriberTest extends TestCase
{
    public function testIsSubscribedToAllEvents(): void
    {
        $eventSubscriber = new PersistDomainEventSubscriber();
        $this->assertTrue($eventSubscriber->isSubscribedTo($this->createMock(DomainEvent::class)));
    }

    public function testInsertEventInDB(): void
    {
        $eventSubscriber = new PersistDomainEventSubscriber();
        $occurredOn = new \DateTime();

        $domainEvent = $this->createMock(DomainEvent::class);

        $domainEvent->method('jsonSerialize')->willReturn(['test' => 'test']);
        $domainEvent->method('occurredOn')->willReturn($occurredOn);

        DB::table('event')->whereRaw('0=0')->delete();
        $this->assertDatabaseCount('event', 0);

        $eventSubscriber->handle($domainEvent);

        $this->assertDatabaseCount('event', 1);
        $this->assertDatabaseHas('event', [
            'event_name' => get_class($domainEvent),
            'event_data' => json_encode($domainEvent),
            'occurred_on' => $occurredOn->format('Y-m-d H:i:s')
        ]);

    }
}
