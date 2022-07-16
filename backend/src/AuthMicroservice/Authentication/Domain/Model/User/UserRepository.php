<?php

namespace AuthMicroservice\Authentication\Domain\Model\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function persistir(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function ofEmail(string $email): User;

    public function ofId(UuidInterface $userId): User;

    public function remove(User $user): void;

    /**
     * @return User[]
     */
    public function search(array $filters): array;
}
