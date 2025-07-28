<?php

namespace Authentication\Domain\Model\Client;

use Ramsey\Uuid\UuidInterface;

interface ClientRepository
{
    /**
     * @throws ClientNotFoundException
     */
    public function ofName(string $name): Client;

    public function persist(Client $client): void;

    public function nextId(): UuidInterface;
}
