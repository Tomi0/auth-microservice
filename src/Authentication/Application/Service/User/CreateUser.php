<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\User\UserCreated;
use Authentication\Domain\Model\User\UserRepository;

class CreateUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(CreateUserRequest $createUserRequest): void
    {
        $attributes = [
            'username' => $createUserRequest->getUsername(),
            'email' => $createUserRequest->getEmail(),
            'password' => $createUserRequest->getPassword(),
        ];

        $userUuid = $this->userRepository->create($attributes);

        event(new UserCreated($userUuid));
    }
}
