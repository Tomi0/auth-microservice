<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostNotFoundException;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostRepository;
use Authentication\Domain\Model\AuthorizedHost\HostNotAuthorized;
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
    private AuthorizedHostRepository $autorizedHostRepository;

    public function __construct(UserRepository           $userRepository,
                                AuthorizedHostRepository $autorizedHostRepository,
                                GenerateJwtToken         $generateJwtToken,
                                CheckPasswordHash        $checkPasswordHash)
    {
        $this->userRepository = $userRepository;
        $this->generateJwtToken = $generateJwtToken;
        $this->checkPasswordHash = $checkPasswordHash;
        $this->autorizedHostRepository = $autorizedHostRepository;
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function handle(LoginUserRequest $loginUserRequest): string
    {
        try {
            $this->autorizedHostRepository->ofHostName($loginUserRequest->hostName);
        } catch (AuthorizedHostNotFoundException $e) {
            throw new HostNotAuthorized('Host ' . $loginUserRequest->hostName . ' not authorized');
        }

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
