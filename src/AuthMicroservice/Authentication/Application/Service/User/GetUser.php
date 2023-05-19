<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use Ramsey\Uuid\Uuid;

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

        return $this->userRepository->ofId(Uuid::fromString($getUserRequest->userId));
    }
}
