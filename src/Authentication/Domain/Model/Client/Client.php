<?php

namespace Authentication\Domain\Model\Client;

use Authentication\Application\Service\Client\ClientCreated;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Service\EventPublisher;

class Client
{
    private UuidInterface $id;
    private string $name;
    private string $clientSecret;
    private string $redirectUrl;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(UuidInterface $id, string $name, string $clientSecret, string $redirectUrl)
    {
        $this->id = $id;
        $this->redirectUrl = $redirectUrl;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->clientSecret = $clientSecret;
        $this->name = $name . '-' . $id->toString();

        EventPublisher::instance()->publish(
            new ClientCreated($this->id())
        );
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function clientSecret(): string
    {
        return $this->clientSecret;
    }

    public function redirectUrl(): string
    {
        return $this->redirectUrl;
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
