<?php

namespace Authentication\Application\Service\User;

readonly class GetUserRequest
{

    public function __construct(public string $userId,
                                public string $authenticatedUser)
    {
    }
}
