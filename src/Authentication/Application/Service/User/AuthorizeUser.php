<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\AuthorizationCode\InvalidRedirectUrlException;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\UserAuthorized;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use DateTimeImmutable;
use Exception;
use Shared\Domain\Service\EventPublisher;
use Shared\Domain\Service\GetConfigItem;
use Shared\Domain\Service\RandomStringGenerator;

class AuthorizeUser
{
    private UserRepository $userRepository;
    private CheckPasswordHash $checkPasswordHash;
    private ClientRepository $clientRepository;
    private AuthorizationCodeRepository $authorizationCodeRepository;
    private RandomStringGenerator $randomStringGenerator;
    private GetConfigItem $getConfigItem;

    public function __construct(UserRepository              $userRepository,
                                ClientRepository            $clientRepository,
                                AuthorizationCodeRepository $authorizationCodeRepository,
                                RandomStringGenerator       $randomStringGenerator,
                                GetConfigItem               $getConfigItem,
                                CheckPasswordHash           $checkPasswordHash)
    {
        $this->userRepository = $userRepository;
        $this->checkPasswordHash = $checkPasswordHash;
        $this->clientRepository = $clientRepository;
        $this->authorizationCodeRepository = $authorizationCodeRepository;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->getConfigItem = $getConfigItem;
    }

    /**
     * @throws ClientNotFoundException
     * @throws InvalidCredentialsException
     * @throws InvalidRedirectUrlException
     * @throws Exception
     */
    public function handle(AuthorizeUserRequest $authorizeUserRequest): AuthorizationCode
    {
        $client = $this->clientRepository->ofName($authorizeUserRequest->clientName);

        if ($client->isValidRedirectUrl($authorizeUserRequest->redirectUrl) === false) {
            throw new InvalidRedirectUrlException('Invalid redirect URL');
        }

        try {
            $user = $this->userRepository->ofEmail($authorizeUserRequest->email);
        } catch (UserNotFoundException) {
            throw new InvalidCredentialsException();
        }

        if (false === $this->checkPasswordHash->execute($authorizeUserRequest->password, $user->password())) {
            throw new InvalidCredentialsException();
        }

        $authorizationCodeId = $this->authorizationCodeRepository->nextId();
        $configExpiresAtMinutes = $this->getConfigItem->execute('auth.authorization_code.expires_at_minutes');

        $authorizationCode = new AuthorizationCode(
            $authorizationCodeId,
            $client->id(),
            $user->id(),
            $authorizationCodeId . '-' . $this->randomStringGenerator->execute(32),
            new DateTimeImmutable('+' . $configExpiresAtMinutes . ' minutes'),
        );

        EventPublisher::instance()->publish(new UserAuthorized(
            $authorizationCode->id(),
            $user->id(),
            $client->id()
        ));

        return $authorizationCode;

    }
}
