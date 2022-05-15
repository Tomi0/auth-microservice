<?php

namespace AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\User\User;

class GetTokenResetPasswordRequest
{
    private string $email;
    private User $userLogged;

    public function __construct(string $email, User $userLogged)
    {
        $this->email = $email;
        $this->userLogged = $userLogged;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function userLogged(): User
    {
        return $this->userLogged;
    }
}
