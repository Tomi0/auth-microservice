<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\GenerateJwtToken;

class LoginUser
{
    private UserRepository $userRepository;
    private GenerateJwtToken $generateJwtToken;
    private CheckPasswordHash $checkPasswordHash;

    public function __construct(UserRepository $userRepository,
                                GenerateJwtToken $generateJwtToken,
                                CheckPasswordHash $checkPasswordHash)
    {
        $this->userRepository = $userRepository;
        $this->generateJwtToken = $generateJwtToken;
        $this->checkPasswordHash = $checkPasswordHash;
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function handle(LoginUserRequest $loginUserRequest): string
    {
        try {
            $user = $this->userRepository->ofEmail($loginUserRequest->email);
        } catch (UserNotFoundException $e) {
            throw new InvalidCredentialsException();
        }

        if ($this->checkPasswordHash->execute($loginUserRequest->password, $user->password()))
            return $this->generateJwtToken->execute($user);


        throw new InvalidCredentialsException();
    }
}
