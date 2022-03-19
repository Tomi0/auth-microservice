<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\InvalidCredentialsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Authentication\Domain\Service\User\CheckEmailAndPasswordMatch;
use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;

class LoginUser
{
    private UserRepository $userRepository;
    private CheckEmailAndPasswordMatch $checkEmailAndPasswordMatch;
    private GenerateJwtToken $generateJwtToken;

    public function __construct(UserRepository $userRepository,
                                CheckEmailAndPasswordMatch $checkEmailAndPasswordMatch,
                                GenerateJwtToken $generateJwtToken)
    {
        $this->userRepository = $userRepository;
        $this->checkEmailAndPasswordMatch = $checkEmailAndPasswordMatch;
        $this->generateJwtToken = $generateJwtToken;
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function handle(LoginUserRequest $loginUserRequest): string
    {
        if (!$this->checkEmailAndPasswordMatch->execute($loginUserRequest->getEmail(), $loginUserRequest->getPassword()))
            throw new InvalidCredentialsException();

        $user = $this->userRepository->ofEmailOrFail($loginUserRequest->getEmail());

        return $this->generateJwtToken->execute($user);
    }
}
