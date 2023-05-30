<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use Illuminate\Support\Facades\Hash;
use Authentication\Domain\Service\User\EncodePassword;

class EncodePasswordLaravel extends EncodePassword
{
    public function execute(string $password): string
    {
        return Hash::make($password);
    }
}
