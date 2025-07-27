<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\Client;

use Authentication\Application\Service\Client\CreateClient;
use Authentication\Application\Service\Client\CreateClientRequest;
use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\EncodePassword;
use Authentication\Infrastructure\Laravel\Domain\Model\AuthorizedHost\ClientInMemoryRepository;
use Shared\Domain\Service\RandomStringGenerator;
use Tests\TestCase;

class CreateClientTest extends TestCase
{
    private ClientInMemoryRepository $clientRepository;
    private CreateClient $createClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientRepository = $this->app->make(ClientRepository::class);
        $this->createClient = new CreateClient(
            $this->clientRepository,
            $this->app->make(RandomStringGenerator::class),
            $this->app->make(EncodePassword::class),
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

    public function testThereIsOneClientInRepository(): void
    {
        $this->assertCount(0, $this->clientRepository->clients);

        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        $this->assertCount(1, $this->clientRepository->clients);
    }

    public function testNameIsSaved(): void
    {
        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        $client = $this->clientRepository->clients[0];
        $this->assertEquals('Test Client', $client->name());
    }

    public function testRedirectUrlIsSaved(): void
    {
        $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        $client = $this->clientRepository->clients[0];
        $this->assertEquals('https://example.com/callback', $client->redirectUrl());
    }

    public function testClientSecretIsSavedEncoded(): void
    {
        $clientSecret = $this->createClient->handle(new CreateClientRequest(
            'Test Client',
            'https://example.com/callback'
        ));

        /** @var Client $client */
        $client = $this->clientRepository->clients[0];
        $this->assertNotEmpty($clientSecret);
        $this->assertNotEquals($clientSecret, $client->clientSecret());

        /** @var CheckPasswordHash $checkPasswordHash */
        $checkPasswordHash = $this->app->make(CheckPasswordHash::class);

        $this->assertTrue($checkPasswordHash->execute($clientSecret, $client->clientSecret()));
    }

}
