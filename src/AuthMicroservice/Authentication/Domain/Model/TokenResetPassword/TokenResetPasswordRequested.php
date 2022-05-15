<?php

namespace AuthMicroservice\Authentication\Domain\Model\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Shared\Domain\Service\DomainEvent;

class TokenResetPasswordRequested implements DomainEvent
{
    private User $userToResetPassword;
    private User $userRequestedResetPasswrod;
    private TokenResetPassword $tokenResetPassword;

    public function __construct(User $userToResetPassword, User $userRequestedResetPasswrod, TokenResetPassword $tokenResetPassword)
    {
        $this->userToResetPassword = $userToResetPassword;
        $this->userRequestedResetPasswrod = $userRequestedResetPasswrod;
        $this->tokenResetPassword = $tokenResetPassword;
    }

    public function userToResetPassword(): User
    {
        return $this->userToResetPassword;
    }

    public function userRequestedResetPasswrod(): User
    {
        return $this->userRequestedResetPasswrod;
    }

    public function tokenResetPassword(): TokenResetPassword
    {
        return $this->tokenResetPassword;
    }
}
