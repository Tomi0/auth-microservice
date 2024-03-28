<?php

namespace Authentication\Domain\Model\SigningKey;

use DateTime;
use Shared\Domain\Service\EventPublisher;

class SigningKey
{
    private string $id;
    private string $privateKey;
    private string $publicKey;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

        $privateKeyString = null;
        $privateKey = openssl_pkey_new();
        $privateKeyDetails = openssl_pkey_get_details($privateKey);
        openssl_pkey_export($privateKey, $privateKeyString);

        $this->privateKey = $privateKeyString;
        $this->publicKey = $privateKeyDetails['key'];

        EventPublisher::instance()->publish(new SigingKeyCreated($this->id(), $this->publicKey()));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function privateKey(): string
    {
        return $this->privateKey;
    }

    public function publicKey(): string
    {
        return $this->publicKey;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
