<?php

namespace Authentication\Application\Service\TokenResetPassword;

readonly class GenerateTokenResetPasswordRequest
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
