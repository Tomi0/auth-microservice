<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use Illuminate\Support\Facades\Hash;
use Authentication\Domain\Service\User\CheckPasswordHash;

class CheckPasswordHashLaravel extends CheckPasswordHash
{

    public function execute(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
