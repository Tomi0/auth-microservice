<?php

namespace Authentication\Domain\Model\User;

use DateTime;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Service\EventPublisher;

class User implements JsonSerializable
{
    private string $id;
    private string $fullName;
    private string $email;
    private string $password;
    private bool $admin;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $id, string $fullName, string $email, string $password)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->admin = false;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

        EventPublisher::instance()->publish(
            new UserCreated($this->fullName(), $this->email())
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function admin(): bool
    {
        return $this->admin;
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
            'admin' => $this->admin(),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        ];
    }

    public function changePassword(string $passwordEncoded): void
    {
        $this->password = $passwordEncoded;

        EventPublisher::instance()->publish(
            new UserPasswordChanged($this->id(), $this->fullName(), $this->email())
        );
    }
}
