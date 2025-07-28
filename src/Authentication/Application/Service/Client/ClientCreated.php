<?php

namespace Authentication\Application\Service\Client;

use DateTime;
use Shared\Domain\Model\DomainEvent;

class ClientCreated implements DomainEvent
{
    private string $clientId;
    private DateTime $occurredOn;

    public function __construct(string $clientId)
    {
        $this->occurredOn = new DateTime();
        $this->clientId = $clientId;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function jsonSerialize(): array
    {
        return [
            'clientId' => $this->clientId,
            'occurredOn' => $this->occurredOn->format(DATE_ATOM),
        ];
    }
}
