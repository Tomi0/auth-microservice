<?php

namespace Authentication\Domain\Model\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;

interface UserRepository
{
    public function create(array $attributes): string;

    /**
     * @param string $email
     * @return object
     * @throws ModelNotFoundException
     */
    public function ofEmailOrFail(string $email): object;
}
