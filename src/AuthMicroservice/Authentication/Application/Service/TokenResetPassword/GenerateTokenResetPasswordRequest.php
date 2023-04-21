<?php

namespace AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\User\User;

readonly class GenerateTokenResetPasswordRequest
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
