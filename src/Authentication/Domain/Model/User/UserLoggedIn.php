<?php

namespace Authentication\Domain\Model\User;

use DateTime;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Model\DomainEvent;

class UserLoggedIn implements DomainEvent
{
    private UuidInterface $userId;
    private string $userFullName;
    private string $userEmail;
    private DateTime $occurredOn;

    public function __construct(UuidInterface $userId, string $userFullName, string $userEmail)
    {
        $this->userId = $userId;
        $this->userFullName = $userFullName;
        $this->userEmail = $userEmail;
        $this->occurredOn = new DateTime();
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->userId,
            'userFullName' => $this->userFullName,
            'userEmail' => $this->userEmail,
        ];
    }
}
