<?php

namespace Tests;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\EventSubscriber;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ClientRepository $clientRepository;
    protected SigningKeyRepository $signingKeyRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initDefaultConfiguration();
    }

    /**
     * @throws Exception
     */
    protected function assertEventsPublished(string|array $domainEventClassNames): void
    {
        if (is_string($domainEventClassNames)) {
            $domainEventClassNames = [$domainEventClassNames];
        }

        $mock = $this->createMock(EventSubscriber::class);

        $mock->method('isSubscribedTo')->willReturn(true);
        $mock->expects($this->exactly(count($domainEventClassNames)))
            ->method('handle')
            ->with($this->callback(function ($event) use ($domainEventClassNames) {
                foreach ($domainEventClassNames as $domainEventClassName) {
                    if ($event instanceof $domainEventClassName) {
                        return true;
                    }
                }
                return false;
            }));
        EventPublisher::instance()->subscribe(
            $mock
        );
    }

    protected function initDefaultConfiguration(): SigningKey
    {
        $this->signingKeyRepository = $this->app->make(SigningKeyRepository::class);
        $this->clientRepository = $this->app->make(ClientRepository::class);

        /** @var SigningKey $signingKey */
        $signingKey = entity(SigningKey::class)->make();
        $client = entity(Client::class)->make([
            'signing_key_id' => $signingKey->id()
        ]);

        $this->signingKeyRepository->persist($signingKey);
        $this->clientRepository->persist($client);

        return $signingKey;
    }
}
