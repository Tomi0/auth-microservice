<?php

namespace Authentication\Domain\Model\User;

use DateTime;
use DateTimeInterface;
use Shared\Domain\Model\DomainEvent;

class UserAuthorized implements DomainEvent
{
    private string $authorizationCodeId;
    private string $userId;
    private string $clientId;
    private DateTime $occurredOn;

    public function __construct(string $authorizationCodeId,
                                string $userId,
                                string $clientId)
    {
        $this->authorizationCodeId = $authorizationCodeId;
        $this->userId = $userId;
        $this->clientId = $clientId;

        $this->occurredOn = new DateTime();
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'authorizationCodeId' => $this->authorizationCodeId,
            'userId' => $this->userId,
            'clientId' => $this->clientId,
            'occurredOn' => $this->occurredOn->format(DateTimeInterface::ATOM),
        ];
    }
}
