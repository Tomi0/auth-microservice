<?php

namespace Authentication\Domain\Model\TokenResetPassword;

use Authentication\Domain\Model\User\User;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Service\EventPublisher;
use UnexpectedValueException;

class TokenResetPassword
{
    private UuidInterface $id;
    private string $email;
    private string $token;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(User $user, string $token)
    {
        $actualDate = new DateTime();
        $this->email = $user->email();
        $this->changeToken($token);

        $this->createdAt = $actualDate;
        $this->updatedAt = clone $actualDate;

        EventPublisher::instance()->publish(
            new TokenResetPasswordGenerated($user->fullName(), $this->email(), $this->token())
        );
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
