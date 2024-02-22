<?php

namespace Shared\Domain\Service;

use BadMethodCallException;
use Shared\Domain\Model\DomainEvent;

class EventPublisher
{
    private static ?self $instance = null;
    /**
     * @var EventSubscriber[]
     */
    private array $subscribers;

    public static function instance(): static
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->subscribers = [];
    }

    public function __clone(): void
    {
        throw new BadMethodCallException('Clone not supported');
    }

    public function subscribe(EventSubscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    public function publish(DomainEvent $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }
}
