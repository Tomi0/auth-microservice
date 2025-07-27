<?php

namespace Authentication\Domain\Model\Client;

use Ramsey\Uuid\UuidInterface;

interface ClientRepository
{
    /**
     * @throws ClientNotFoundException
     */
    public function ofHostName(string $hostName): Client;

    public function persist(Client $client): void;

    public function nextId(): UuidInterface;
}
