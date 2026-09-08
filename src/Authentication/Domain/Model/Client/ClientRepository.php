<?php

namespace Authentication\Domain\Model\Client;

interface ClientRepository
{
    /**
     * @throws ClientNotFoundException
     */
    public function ofName(string $name): Client;

    public function persist(Client $client): void;

    public function nextId(): string;
}
