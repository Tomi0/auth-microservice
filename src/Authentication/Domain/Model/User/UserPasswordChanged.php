<?php

namespace Authentication\Domain\Model\User;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Model\DomainEvent;

class UserPasswordChanged implements DomainEvent
{
    private string $userId;
    private string $fullName;
    private string $email;
    private DateTime $occurredOn;

    public function __construct(string $userId, string $fullName, string $email)
    {
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->occurredOn = new DateTime();
    }

    public function userId(): string
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

    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->userId(),
            'fullName' => $this->fullName(),
            'email' => $this->email(),
        ];
    }
}
