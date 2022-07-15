<?php

namespace AuthMicroservice\Authentication\Domain\Model\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Shared\Domain\Service\DomainEvent;

class TokenResetPasswordGenerated implements DomainEvent
{
    private User $userToResetPassword;
    private TokenResetPassword $tokenResetPassword;

    public function __construct(User $userToResetPassword, TokenResetPassword $tokenResetPassword)
    {
        $this->userToResetPassword = $userToResetPassword;
        $this->tokenResetPassword = $tokenResetPassword;
    }

    public function userToResetPassword(): User
    {
        return $this->userToResetPassword;
    }

    public function tokenResetPassword(): TokenResetPassword
    {
        return $this->tokenResetPassword;
    }
}
