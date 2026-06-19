<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\Client;

use Authentication\Application\Service\Client\ClientCreated;
use Authentication\Application\Service\Client\CreateClient;
use Authentication\Application\Service\Client\CreateClientRequest;
use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKeyCreated;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\EncodePassword;
use Authentication\Infrastructure\Laravel\Domain\Model\Client\ClientInMemoryRepository;
use Shared\Domain\Service\RandomStringGenerator;
use Tests\TestCase;

class CreateClientTest extends TestCase
{
    private CreateClient $createClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createClient = new CreateClient(
            $this->clientRepository,
            $this->app->make(RandomStringGenerator::class),
            $this->app->make(EncodePassword::class),
            $this->signingKeyRepository,
        );
    }

    public function testReturnString(): void
    {
        $result = $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        $this->assertIsString($result);
    }

    public function testThereIsMoreClientInRepository(): void
    {
        $this->assertCount(1, $this->clientRepository->clients);

        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        $this->assertCount(2, $this->clientRepository->clients);
    }

    public function testNameIsSaved(): void
    {
        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        /** @var Client $client */
        $client = $this->clientRepository->clients[count($this->clientRepository->clients) - 1];
        $this->assertEquals('Test Client-' . $client->id(), $client->name());
    }

    public function testRedirectUrlIsSaved(): void
    {
        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        $client = $this->clientRepository->clients[count($this->clientRepository->clients) - 1];
        $this->assertEquals('https://example.com/callback', $client->redirectUrl());
    }

    public function testClientSecretIsSavedEncoded(): void
    {
        $clientSecret = $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        /** @var Client $client */
        $client = $this->clientRepository->clients[count($this->clientRepository->clients) - 1];
        $this->assertNotEmpty($clientSecret);
        $this->assertNotEquals($clientSecret, $client->clientSecret());

        /** @var CheckPasswordHash $checkPasswordHash */
        $checkPasswordHash = $this->app->make(CheckPasswordHash::class);

        $this->assertTrue($checkPasswordHash->execute($clientSecret, $client->clientSecret()));
    }

    public function testFireUserCreated(): void
    {
        $this->assertEventsPublished([SigningKeyCreated::class, ClientCreated::class]);

        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));
    }

}
