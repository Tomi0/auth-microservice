<?php

namespace Authentication\Application\Service\User;

readonly class ChangeUserPasswordRequest
{
    public string $tokenResetPassword;
    public string $password;
    public string $userEmail;

    public function __construct(string $tokenResetPassword, string $password, string $userEmail)
    {
        $this->tokenResetPassword = $tokenResetPassword;
        $this->password = $password;
        $this->userEmail = $userEmail;
    }
}
