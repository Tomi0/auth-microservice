<?php

namespace Authentication\Domain\Model\User;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Model\DomainEvent;

class UserPasswordChanged implements DomainEvent
{
    private UuidInterface $userId;
    private string $fullName;
    private string $email;
    private DateTime $occurredOn;

    public function __construct(UuidInterface $userId, string $fullName, string $email)
    {
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->occurredOn = new DateTime();
    }

    public function userId(): UuidInterface
    {
        return $this->userId;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
