<?php

namespace Authentication\Domain\Model\User;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Shared\Domain\Service\DomainEvent;

class UserPasswordChanged implements DomainEvent
{
    private User $user;
    private TokenResetPassword $tokenResetPassword;

    public function __construct(User $user, TokenResetPassword $tokenResetPassword)
    {
        $this->user = $user;
        $this->tokenResetPassword = $tokenResetPassword;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function tokenResetPassword(): TokenResetPassword
    {
        return $this->tokenResetPassword;
    }
}
