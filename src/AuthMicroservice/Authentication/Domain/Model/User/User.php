<?php

namespace AuthMicroservice\Authentication\Domain\Model\User;

use DateTime;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class User implements JsonSerializable
{
    private UuidInterface $id;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    private function toArray()
    {
        return [
            'id' => $this->id(),
            'email' => $this->email(),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        ];
    }
}