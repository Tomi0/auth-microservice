<?php

namespace Authentication\Domain\Model\User;

use DateTime;
use Shared\Domain\Model\DomainEvent;

class UserCreated implements DomainEvent
{
    private User $user;
    private DateTime $occurredOn;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->occurredOn = new DateTime();
    }

    public function user(): User
    {
        return $this->user;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
