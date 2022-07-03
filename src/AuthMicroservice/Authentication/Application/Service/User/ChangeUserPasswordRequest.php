<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

class ChangeUserPasswordRequest
{
    private string $tokenResetPassword;
    private string $password;
    private string $userEmail;

    public function __construct(string $tokenResetPassword, string $password, string $userEmail)
    {
        $this->tokenResetPassword = $tokenResetPassword;
        $this->password = $password;
        $this->userEmail = $userEmail;
    }

    public function tokenResetPassword(): string
    {
        return $this->tokenResetPassword;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function userEmail(): string
    {
        return $this->userEmail;
    }
}
