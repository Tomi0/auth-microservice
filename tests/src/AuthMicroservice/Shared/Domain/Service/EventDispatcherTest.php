<?php

namespace Tests\src\AuthMicroservice\Shared\Domain\Service;

use DateTime;
use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\EventDispatcher;
use Tests\TestCase;

class EventDispatcherTest extends TestCase
{
    private EventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutEvents();
        $this->eventDispatcher = $this->app->make(EventDispatcher::class);
    }

    public function testFireDomainEvent(): void
    {
        $this->expectsEvents(DomainEvent::class);

        $this->eventDispatcher->execute(new class() implements DomainEvent {
            public function occurredOn(): DateTime
            {
                return new DateTime();
            }
        });
    }

}
