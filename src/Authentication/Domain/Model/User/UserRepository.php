<?php

namespace Authentication\Domain\Model\User;

interface UserRepository
{
    public function nextId(): string;

    public function persist(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function ofEmail(string $email): User;

    /**
     * @throws UserNotFoundException
     */
    public function ofId(string $userId): User;

    public function remove(User $user): void;

    /**
     * @return User[]
     */
    public function search(array $filters): array;
}
