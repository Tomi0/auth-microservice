<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\EventSubscriber;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function assertEventPublished(string $domainEventClassName): void
    {
        $mock = $this->createMock(EventSubscriber::class);

        $mock->method('isSubscribedTo')->willReturn(true);
        $mock->expects($this->once())->method('handle')->with($this->isInstanceOf($domainEventClassName));
        EventPublisher::instance()->subscribe(
            $mock
        );
    }

    public function beginDatabaseTransaction(): void
    {
        $connection = $this->app->make('em')->getConnection();
        $connection->beginTransaction();

        $this->beforeApplicationDestroyed(function () use ($connection) {
            $connection->rollback();
        });
    }

    protected function getJwtToken(User $user = null): string
    {
        $user = ($user === null) ? entity(User::class)->create() : $user;

        /** @var GenerateJwtToken $configuration */
        $configuration = $this->app->make(GenerateJwtToken::class);
        return 'Bearer ' . $configuration->execute($user);
    }
}
