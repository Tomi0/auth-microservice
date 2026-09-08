<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\AuthorizationCode\InvalidAuthorizationCodeException;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class AuthorizationCodeDoctrineRepository extends EntityRepository implements AuthorizationCodeRepository
{

    public function nextId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function persist(AuthorizationCode $authorizationCode): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($authorizationCode);
        $entityManager->flush();
    }

    public function ofCode(string $code): AuthorizationCode
    {
        $authorizationCode = $this->findOneBy(['code' => $code]);

        if (null === $authorizationCode) {
            throw new InvalidAuthorizationCodeException("Authorization code not found: {$code}");
        }

        return $authorizationCode;
    }
}
