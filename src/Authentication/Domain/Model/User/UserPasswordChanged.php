<?php

namespace Authentication\Domain\Model\User;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use DateTime;
use Shared\Domain\Model\DomainEvent;

class UserPasswordChanged implements DomainEvent
{
    private User $user;
    private TokenResetPassword $tokenResetPassword;
    private DateTime $occurredOn;

    public function __construct(User $user, TokenResetPassword $tokenResetPassword)
    {
        $this->user = $user;
        $this->tokenResetPassword = $tokenResetPassword;
        $this->occurredOn = new DateTime();
    }

    public function user(): User
    {
        return $this->user;
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
