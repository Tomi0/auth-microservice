<?php

namespace Tests\src\AuthMicroservice\Shared\Domain\Service;

use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\EventSubscriber;
use Tests\TestCase;

class EventPublisherTest extends TestCase
{

    public function testGetInstanceSingleton(): void
    {
        $instance1 = EventPublisher::instance();

        $instance2 = EventPublisher::instance();

        $this->assertSame($instance1, $instance2);
    }

    public function testCanPublishDomainEvent(): void
    {
        $subscriber1 = new class() implements EventSubscriber {
            public bool $called = false;
            public function isSubscribedTo(DomainEvent $event): bool
            {
                return true;
            }

            public function handle(DomainEvent $event): void
            {
                $this->called = true;
            }
        };
        $subscriber2 = new class() implements EventSubscriber {
            public bool $called = false;
            public function isSubscribedTo(DomainEvent $event): bool
            {
                return true;
            }

            public function handle(DomainEvent $event): void
            {
                $this->called = true;
            }
        };

        $eventPublisher = EventPublisher::instance();

        $eventPublisher->subscribe($subscriber1);
        $eventPublisher->subscribe($subscriber2);

        $this->assertFalse($subscriber1->called);
        $this->assertFalse($subscriber2->called);

        $mock = $this->createMock(DomainEvent::class);
        $mock->method('occurredOn')->willReturn(new \DateTime());
        $eventPublisher->publish($mock);

        $this->assertTrue($subscriber1->called);
        $this->assertTrue($subscriber2->called);
    }
}
