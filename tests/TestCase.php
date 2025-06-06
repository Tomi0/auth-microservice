<?php

namespace Tests;

use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\EventSubscriber;
use Shared\Infrastructure\Laravel\Domain\Service\PersistDomainEventSubscriber;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected SigningKeyRepository $signingKeyRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instanceSigningKeyRepository();
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

    protected function getJwtToken(User $user = null): string
    {
        $user = ($user === null) ? entity(User::class)->make() : $user;
        $signingKey = $this->instanceSigningKeyRepository();

        /** @var GenerateJwtToken $generateJwtToken */
        $generateJwtToken = $this->app->make(GenerateJwtToken::class);
        return 'Bearer ' . $generateJwtToken->execute($user, $signingKey);
    }

    protected function instanceSigningKeyRepository(): SigningKey
    {
        $this->signingKeyRepository = $this->app->make(SigningKeyRepository::class);
        $signingKey = entity(SigningKey::class)->make();
        $this->signingKeyRepository->persist(
            $signingKey
        );
        return $signingKey;
    }
}
