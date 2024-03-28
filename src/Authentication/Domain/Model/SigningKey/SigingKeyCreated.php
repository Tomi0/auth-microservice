<?php

namespace Authentication\Domain\Model\SigningKey;

use DateTime;
use Shared\Domain\Model\DomainEvent;

class SigingKeyCreated implements DomainEvent
{
    private string $id;
    private string $publicKey;
    private DateTime $occurredOn;

    public function __construct(string $id, string $publicKey)
    {
        $this->id = $id;
        $this->publicKey = $publicKey;
        $this->occurredOn = new DateTime();
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'publicKey' => $this->publicKey,
            'occurredOn' => $this->occurredOn->format('Y-m-d H:i:s')
        ];
    }
}
