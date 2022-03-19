<?php

namespace AuthMicroservice\Authentication\Domain\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

abstract class CheckEmailAndPasswordMatch
{
    public abstract function execute(string $email, string $password): bool;
}
