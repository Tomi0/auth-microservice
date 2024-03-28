<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostNotFoundException;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostRepository;
use Authentication\Domain\Model\AuthorizedHost\HostNotAuthorized;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\UserLoggedIn;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\GetConfigItem;

class LoginUser
{
    private UserRepository $userRepository;
    private GenerateJwtToken $generateJwtToken;
    private CheckPasswordHash $checkPasswordHash;
    private AuthorizedHostRepository $autorizedHostRepository;
    private GetConfigItem $getConfigItem;
    private SigningKeyRepository $signingKey;

    public function __construct(UserRepository           $userRepository,
                                AuthorizedHostRepository $autorizedHostRepository,
                                GenerateJwtToken         $generateJwtToken,
                                GetConfigItem            $getConfigItem,
                                SigningKeyRepository     $signingKey,
                                CheckPasswordHash        $checkPasswordHash)
    {
        $this->userRepository = $userRepository;
        $this->generateJwtToken = $generateJwtToken;
        $this->checkPasswordHash = $checkPasswordHash;
        $this->autorizedHostRepository = $autorizedHostRepository;
        $this->getConfigItem = $getConfigItem;
        $this->signingKey = $signingKey;
    }

    /**
     * @throws InvalidCredentialsException
     * @throws HostNotAuthorized
     * @throws SigningKeyNotFoundException
     */
    public function handle(LoginUserRequest $loginUserRequest): string
    {
        if ((bool)$this->getConfigItem->execute('auth.enable_authorized_host')) {
            try {
                $this->autorizedHostRepository->ofHostName($loginUserRequest->hostName);
            } catch (AuthorizedHostNotFoundException $e) {
                throw new HostNotAuthorized('Host ' . $loginUserRequest->hostName . ' not authorized');
            }
        }

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
