<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\UserDeleted;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;

class DeleteUser
{
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcher $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws UserHasNotPermissionsException
     * @throws UserNotFoundException
     */
    public function handle(DeleteUserRequest $deleteUserRequest): void
    {
        if (!$deleteUserRequest->userAuthenticated()->admin())
            throw new UserHasNotPermissionsException('User can not perform this action.');

        $user = $this->userRepository->ofId($deleteUserRequest->userId());

        $this->userRepository->remove($user);

        $this->eventDispatcher->execute(new UserDeleted($user));
    }
}
