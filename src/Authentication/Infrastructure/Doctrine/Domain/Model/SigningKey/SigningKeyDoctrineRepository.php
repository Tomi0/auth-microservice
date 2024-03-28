<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\SigningKey;

use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class SigningKeyDoctrineRepository extends EntityRepository implements SigningKeyRepository
{

    public function nextId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function persist(SigningKey $sigingKey)
    {
        $em = $this->getEntityManager();
        $em->persist($sigingKey);
        $em->flush();
    }

    /**
     * @inheritDoc
     */
    public function first(): SigningKey
    {
        $signingKey = $this->findOneBy([]);

        if ($signingKey instanceof SigningKey) {
            return $signingKey;
        }

        throw new SigningKeyNotFoundException('No signing key found');
    }

    public function remove(SigningKey $signingKey): void
    {
        $em = $this->getEntityManager();
        $em->remove($signingKey);
    }
}
