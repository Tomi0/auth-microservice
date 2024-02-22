<?php

namespace Authentication\Domain\Model\User;

use DateTime;
use Shared\Domain\Model\DomainEvent;

class UserCreated implements DomainEvent
{
    private string $fullName;
    private string $email;
    private DateTime $occurredOn;

    public function __construct(string $fullName, string $email)
    {
        $this->occurredOn = new DateTime();
        $this->fullName = $fullName;
        $this->email = $email;
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
            'fullName' => $this->fullName,
            'email' => $this->email,
        ];
    }
}
