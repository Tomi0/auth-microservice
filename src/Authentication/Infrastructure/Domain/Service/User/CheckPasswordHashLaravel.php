<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use Authentication\Domain\Service\User\CheckPasswordHash;
use Illuminate\Support\Facades\Hash;

class CheckPasswordHashLaravel extends CheckPasswordHash
{

    public function execute(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
