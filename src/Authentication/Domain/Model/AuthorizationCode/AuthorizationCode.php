<?php

namespace Authentication\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\Client\Client;
use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;

class AuthorizationCode implements JsonSerializable
{
    private string $id;
    private string $clientId;
    private string $userId;
    private string $code;
    private DateTimeImmutable $expiresAt;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(string $id, string $clientId, string $userId, string $code, DateTimeImmutable $expiresAt)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->userId = $userId;
        $this->code = $code;
        $this->expiresAt = $expiresAt;

        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function belongsToClient(Client $client): bool
    {
        return $this->clientId === $client->id();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function clientId(): string
    {
        return $this->clientId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function expiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'clientId' => $this->clientId,
            'userId' => $this->userId,
            'code' => $this->code,
            'expiresAt' => $this->expiresAt->format(DateTimeInterface::ATOM),
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt->format(DateTimeInterface::ATOM),
        ];
    }
}
