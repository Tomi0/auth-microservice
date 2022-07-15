<?php

namespace AuthMicroservice\Authentication\Domain\Model\TokenResetPassword;

use DateTime;
use Ramsey\Uuid\UuidInterface;
use UnexpectedValueException;

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
        $this->changeToken($token);
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

    public function changeToken(string $token): void
    {
        if (strlen($token) < 16)
            throw new UnexpectedValueException('Token length can not be less than 16');

        $this->token = $token;
    }
}
