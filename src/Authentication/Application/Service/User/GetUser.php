<?php

namespace Authentication\Application\Service\User;

use Ramsey\Uuid\Uuid;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserHasNotPermissionsException;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;

class GetUser
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @throws UserHasNotPermissionsException
     * @throws UserNotFoundException
     */
    public function handle(GetUserRequest $getUserRequest): User
    {
        if ($getUserRequest->userId !== $getUserRequest->authenticatedUser)
            throw new UserHasNotPermissionsException();

        return $this->userRepository->ofId($getUserRequest->userId);
    }
}
