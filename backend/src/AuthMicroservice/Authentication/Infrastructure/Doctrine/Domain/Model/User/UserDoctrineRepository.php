<?php

namespace AuthMicroservice\Authentication\Infrastructure\Doctrine\Domain\Model\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

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

    /**
     * @throws UserNotFoundException
     */
    public function ofId(UuidInterface $userId): User
    {
        $user = $this->find($userId);
        if ($user === null) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function remove(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @inheritDoc
     */
    public function search(array $filters): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder = $queryBuilder->select('u')->from(User::class, 'u');

        if (isset($filters['email'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('u.email', ':email'))->setParameter(':email', '%' . $filters['email'] . '%');
        }
        if (isset($filters['admin'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('u.admin', ':admin'))->setParameter(':admin', $filters['admin']);
        }

        return $queryBuilder->orderBy('u.email', 'ASC')->getQuery()->getResult();
    }
}
