<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\User\EmailAlreadyInUseException;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserNotFoundException;
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

    /**
     * @throws EmailAlreadyInUseException
     */
    public function handle(CreateUserRequest $createUserRequest): User
    {
        try {
            $this->userRepository->ofEmail($createUserRequest->email);

            throw new EmailAlreadyInUseException('email', 'Email already in use');
        } catch (UserNotFoundException $e) {
        }

        $passwordHash = $this->encodePassword->execute($createUserRequest->password);

        $user = new User(
            $this->userRepository->nextId(),
            $createUserRequest->fullName,
            $createUserRequest->email,
            $passwordHash
        );

        $this->userRepository->persist($user);

        return $user;
    }
}
