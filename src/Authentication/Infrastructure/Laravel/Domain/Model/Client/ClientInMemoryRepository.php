<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\Client;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ClientInMemoryRepository implements ClientRepository
{
    /**
     * @var Client[]
     */
    public array $clients = [];

    public function ofName(string $name): Client
    {
        foreach ($this->clients as $client) {
            if ($client->name() === $name) {
                return $client;
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
