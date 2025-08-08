<?php

namespace Authentication\Domain\Model\AccessToken;

use Authentication\Domain\Model\User\User;
use JsonSerializable;

class AccessToken implements JsonSerializable
{
    private string $type;
    private string $token;
    private User $user;

    public function __construct(string $type, string $token, User $user)
    {
        $this->type = $type;
        $this->token = $token;
        $this->user = $user;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'token' => $this->token,
            'userId' => $this->user->id()->toString(),
        ];
    }
}
