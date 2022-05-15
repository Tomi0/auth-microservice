<?php

namespace AuthMicroservice\Authentication\Domain\Model\TokenResetPassword;

use DateTime;
use Ramsey\Uuid\UuidInterface;

class TokenResetPassword
{
    private UuidInterface $id;
    private string $email;
    private string $token;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $email, string $token)
    {
        $actualDate = new DateTime();
        $this->email = $email;
        $this->token = $token;
        $this->createdAt = $actualDate;
        $this->updatedAt = $actualDate;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function token(): string
    {
        return $this->token;
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
