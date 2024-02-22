<?php

namespace Authentication\Domain\Model\TokenResetPassword;

use Authentication\Domain\Model\User\User;
use DateTime;
use Shared\Domain\Model\DomainEvent;

class TokenResetPasswordGenerated implements DomainEvent
{
    private User $userToResetPassword;
    private TokenResetPassword $tokenResetPassword;
    private DateTime $occurredOn;

    public function __construct(User $userToResetPassword, TokenResetPassword $tokenResetPassword)
    {
        $this->userToResetPassword = $userToResetPassword;
        $this->tokenResetPassword = $tokenResetPassword;
        $this->occurredOn = new DateTime();
    }

    public function userToResetPassword(): User
    {
        return $this->userToResetPassword;
    }

    public function tokenResetPassword(): TokenResetPassword
    {
        return $this->tokenResetPassword;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
