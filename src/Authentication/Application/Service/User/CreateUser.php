<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserCreated;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\EncodePassword;

class CreateUser
{
    private UserRepository $userRepository;
    private EncodePassword $encodePassword;

    public function __construct(UserRepository $userRepository, EncodePassword $encodePassword)
    {
        $this->userRepository = $userRepository;
        $this->encodePassword = $encodePassword;
    }

    public function handle(CreateUserRequest $createUserRequest): User
    {
        // TODO unique email
        $passwordHash = $this->encodePassword->execute($createUserRequest->password);

        $user = new User($createUserRequest->fullName, $createUserRequest->email, $passwordHash);

        $this->userRepository->persistir($user);

        return $user;
    }
}
