<?php

namespace Authentication\Domain\Model\User;

use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Model\DomainEvent;

class UserAuthorized implements DomainEvent
{
    private UuidInterface $authorizationCodeId;
    private UuidInterface $userId;
    private UuidInterface $clientId;
    private DateTime $occurredOn;

    public function __construct(UuidInterface $authorizationCodeId,
                                UuidInterface $userId,
                                UuidInterface $clientId)
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
            'authorizationCodeId' => $this->authorizationCodeId->toString(),
            'userId' => $this->userId->toString(),
            'clientId' => $this->clientId->toString(),
            'occurredOn' => $this->occurredOn->format(DateTimeInterface::ATOM),
        ];
    }
}
