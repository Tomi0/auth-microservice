<?php

namespace AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\User\User;

class GenerateTokenResetPasswordRequest
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
