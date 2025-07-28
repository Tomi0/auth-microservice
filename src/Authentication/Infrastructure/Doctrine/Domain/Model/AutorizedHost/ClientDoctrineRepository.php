<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\AutorizedHost;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ClientDoctrineRepository extends EntityRepository implements ClientRepository
{

    /**
     * @inheritDoc
     */
    public function ofName(string $name): Client
    {
        $client = $this->findOneBy(['name' => $name]);

        if ($client === null)
            throw new ClientNotFoundException('Client name ' . $name . ' not found');

        return $client;
    }

    public function persist(Client $client): void
    {
        $em = $this->getEntityManager();
        $em->persist($client);
        $em->flush();
    }

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
