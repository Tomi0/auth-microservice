<?php

namespace Authentication\Domain\Model\User;

use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function persistir(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function ofEmail(string $email): User;

    /**
     * @throws UserNotFoundException
     */
    public function ofId(UuidInterface $userId): User;

    public function remove(User $user): void;

    /**
     * @return User[]
     */
    public function search(array $filters): array;
}
