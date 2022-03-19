<?php

namespace AuthMicroservice\Authentication\Domain\Model\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;

interface UserRepository
{
    public function persistir(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function ofEmail(string $email): User;
}
