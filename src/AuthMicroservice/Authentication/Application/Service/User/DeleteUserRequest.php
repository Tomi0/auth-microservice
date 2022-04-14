<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use Ramsey\Uuid\UuidInterface;

class DeleteUserRequest
{
    private UuidInterface $userId;
    private User $userAuthenticated;

    public function __construct(UuidInterface $userId, User $userAuthenticated) {

        $this->userId = $userId;
        $this->userAuthenticated = $userAuthenticated;
    }

    /**
     * @return UuidInterface
     */
    public function userId(): UuidInterface
    {
        return $this->userId;
    }

    /**
     * @return User
     */
    public function userAuthenticated(): User
    {
        return $this->userAuthenticated;
    }


}
