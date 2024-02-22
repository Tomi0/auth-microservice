<?php

namespace Authentication\Domain\Model\TokenResetPassword;

use Authentication\Domain\Model\User\User;
use DateTime;
use Shared\Domain\Model\DomainEvent;

class TokenResetPasswordGenerated implements DomainEvent
{
    private string $userFullName;
    private string $userEmail;
    private string $tokenResetPassword;
    private DateTime $occurredOn;

    public function __construct(string $userFullName, string $userEmail, string $tokenResetPassword)
    {
        $this->userFullName = $userFullName;
        $this->userEmail = $userEmail;
        $this->tokenResetPassword = $tokenResetPassword;
        $this->occurredOn = new DateTime();
    }

    public function userFullName(): string
    {
        return $this->userFullName;
    }

    public function userEmail(): string
    {
        return $this->userEmail;
    }

    public function tokenResetPassword(): string
    {
        return $this->tokenResetPassword;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
