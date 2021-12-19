<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckEmailAndPasswordMatch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class CheckEmailAndPasswordMatchLaravel extends CheckEmailAndPasswordMatch
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $password): bool
    {
        try {
            $user = $this->userRepository->ofEmailOrFail($email);
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return Hash::check($password, $user['password']);
    }
}
