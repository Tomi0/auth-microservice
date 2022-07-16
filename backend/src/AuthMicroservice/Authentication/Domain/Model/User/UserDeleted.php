<?php

namespace AuthMicroservice\Authentication\Domain\Model\User;

use AuthMicroservice\Shared\Domain\Service\DomainEvent;

class UserDeleted implements DomainEvent
{
    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }
}
