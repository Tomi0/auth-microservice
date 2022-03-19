<?php

namespace AuthMicroservice\Authentication\Infrastructure\Doctrine\Domain\Model\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use Doctrine\ORM\EntityRepository;

class UserDoctrineRepository extends EntityRepository implements UserRepository
{

    /**
     * @inheritDoc
     */
    public function ofEmail(string $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if ($user === null)
            throw new UserNotFoundException();

        return $user;
    }

    public function persistir(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
