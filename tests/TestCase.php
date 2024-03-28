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

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createOrGetSigningKey();
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
        $signingKey = $this->createOrGetSigningKey();

        /** @var GenerateJwtToken $generateJwtToken */
        $generateJwtToken = $this->app->make(GenerateJwtToken::class);
        return 'Bearer ' . $generateJwtToken->execute($user, $signingKey);
    }

    protected function createOrGetSigningKey(): SigningKey
    {
        /** @var SigningKeyRepository $signingKeyRepository */
        $signingKeyRepository = app()->make(SigningKeyRepository::class);

        try {
            $signingKey = $signingKeyRepository->first();
        } catch (SigningKeyNotFoundException $e) {
            $signingKey = new SigningKey(Uuid::uuid4());
            $signingKeyRepository->persist($signingKey);
        }

        return $signingKey;
    }
}
