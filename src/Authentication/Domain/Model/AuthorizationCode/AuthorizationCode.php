<?php

namespace Authentication\Domain\Model\AuthorizationCode;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class AuthorizationCode implements JsonSerializable
{
    private UuidInterface $id;
    private UuidInterface $clientId;
    private UuidInterface $userId;
    private string $code;
    private DateTimeImmutable $expiresAt;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(UuidInterface $id, UuidInterface $clientId, UuidInterface $userId, string $code, DateTimeImmutable $expiresAt)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->userId = $userId;
        $this->code = $code;
        $this->expiresAt = $expiresAt;

        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function clientId(): UuidInterface
    {
        return $this->clientId;
    }

    public function userId(): UuidInterface
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
            'id' => $this->id->toString(),
            'clientId' => $this->clientId->toString(),
            'userId' => $this->userId->toString(),
            'code' => $this->code,
            'expiresAt' => $this->expiresAt->format(DateTimeInterface::ATOM),
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt->format(DateTimeInterface::ATOM),
        ];
    }
}
