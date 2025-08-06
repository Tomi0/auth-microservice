<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AuthorizationCodeDoctrineRepository extends EntityRepository implements AuthorizationCodeRepository
{

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function persist(AuthorizationCode $authorizationCode): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($authorizationCode);
        $entityManager->flush();
    }
}
