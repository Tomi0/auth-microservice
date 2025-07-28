<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use Authentication\Domain\Service\User\EncodePassword;
use Illuminate\Support\Facades\Hash;

class EncodePasswordLaravel extends EncodePassword
{
    public function execute(string $password): string
    {
        return Hash::make($password);
    }
}
