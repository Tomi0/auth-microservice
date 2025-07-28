<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\UserLoggedIn;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Shared\Domain\Service\EventPublisher;

class LoginUser
{
    private UserRepository $userRepository;
    private GenerateJwtToken $generateJwtToken;
    private CheckPasswordHash $checkPasswordHash;
    private ClientRepository $autorizedHostRepository;
    private SigningKeyRepository $signingKey;

    public function __construct(UserRepository       $userRepository,
                                ClientRepository     $autorizedHostRepository,
                                GenerateJwtToken     $generateJwtToken,
                                SigningKeyRepository $signingKey,
                                CheckPasswordHash    $checkPasswordHash)
    {
        $this->userRepository = $userRepository;
        $this->generateJwtToken = $generateJwtToken;
        $this->checkPasswordHash = $checkPasswordHash;
        $this->autorizedHostRepository = $autorizedHostRepository;
        $this->signingKey = $signingKey;
    }

    /**
     * @throws ClientNotFoundException
     * @throws InvalidCredentialsException
     * @throws SigningKeyNotFoundException
     */
    public function handle(LoginUserRequest $loginUserRequest): string
    {
        $this->autorizedHostRepository->ofHostName($loginUserRequest->hostName);

        try {
            $user = $this->userRepository->ofEmail($loginUserRequest->email);
        } catch (UserNotFoundException $e) {
            throw new InvalidCredentialsException();
        }

        if ($this->checkPasswordHash->execute($loginUserRequest->password, $user->password())) {

            $signingKey = $this->signingKey->first();

            $jwtToken = $this->generateJwtToken->execute($user, $signingKey);

            EventPublisher::instance()->publish(
                new UserLoggedIn($user->id(), $user->fullName(), $user->email())
            );

            return $jwtToken;
        }

        throw new InvalidCredentialsException();
    }
}
