<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserCreated;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Authentication\Domain\Service\User\EncodePassword;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;

class CreateUser
{
    private UserRepository $userRepository;
    private EncodePassword $encodePassword;
    private EventDispatcher $eventDispatcher;

    public function __construct(UserRepository $userRepository, EncodePassword $encodePassword, EventDispatcher $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->encodePassword = $encodePassword;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreateUserRequest $createUserRequest): User
    {
        $passwordHash = $this->encodePassword->execute($createUserRequest->password());

        $user = new User($createUserRequest->email(), $passwordHash);

        $this->userRepository->persistir($user);

        $this->eventDispatcher->execute(new UserCreated($user));

        return $user;
    }
}
