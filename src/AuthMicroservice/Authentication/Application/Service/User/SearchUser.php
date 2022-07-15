<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;

class SearchUser
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param SearchUserRequest $searchUserRequest
     * @return User[]
     */
    public function handle(SearchUserRequest $searchUserRequest): array
    {
        $filters = array_filter([
            'email' => $searchUserRequest->email(),
            'admin' => $searchUserRequest->admin(),
        ], function ($value) {
            return $value !== null;
        });

        return $this->userRepository->search($filters);
    }
}
