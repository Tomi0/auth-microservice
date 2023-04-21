<?php

namespace AuthMicroservice\Authentication\Domain\Model\User;

use AuthMicroservice\Shared\Domain\Service\DomainEvent;

class UserCreated implements DomainEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user(): User
    {
        return $this->user;
    }
}
