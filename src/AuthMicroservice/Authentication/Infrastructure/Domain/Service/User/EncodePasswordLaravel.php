<?php

namespace AuthMicroservice\Authentication\Infrastructure\Domain\Service\User;

use AuthMicroservice\Authentication\Domain\Service\User\EncodePassword;
use Illuminate\Support\Facades\Hash;

class EncodePasswordLaravel extends EncodePassword
{
    public function execute(string $password): string
    {
        return Hash::make($password);
    }
}
