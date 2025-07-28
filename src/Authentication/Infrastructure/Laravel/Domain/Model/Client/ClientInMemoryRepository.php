<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\Client;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ClientInMemoryRepository implements ClientRepository
{
    public array $clients = [];

    public function ofHostName(string $hostName): Client
    {
        foreach ($this->clients as $authorizedHost) {
            if ($authorizedHost->hostName() === $hostName) {
                return $authorizedHost;
            }
        }
        throw new ClientNotFoundException();
    }

    public function persist(Client $client): void
    {
        $this->clients[] = $client;
    }

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
