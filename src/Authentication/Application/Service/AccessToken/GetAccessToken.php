<?php

namespace Authentication\Application\Service\AccessToken;


use Authentication\Domain\Model\AccessToken\AccessToken;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\AuthorizationCode\InvalidAuthorizationCodeException;
use Authentication\Domain\Model\Client\ClientNameAndSecretMissmatchException;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\GenerateJwtToken;

class GetAccessToken
{
    private ClientRepository $clientRepository;
    private AuthorizationCodeRepository $authorizationCodeRepository;
    private GenerateJwtToken $jwtToken;
    private UserRepository $userRepository;
    private SigningKeyRepository $signingKeyRepository;

    public function __construct(ClientRepository            $clientRepository,
                                AuthorizationCodeRepository $authorizationCodeRepository,
                                SigningKeyRepository        $signingKeyRepository,
                                UserRepository              $userRepository,
                                GenerateJwtToken            $jwtToken)
    {
        $this->clientRepository = $clientRepository;
        $this->authorizationCodeRepository = $authorizationCodeRepository;
        $this->jwtToken = $jwtToken;
        $this->userRepository = $userRepository;
        $this->signingKeyRepository = $signingKeyRepository;
    }

    /**
     * @throws ClientNameAndSecretMissmatchException
     * @throws ClientNotFoundException
     * @throws InvalidAuthorizationCodeException
     * @throws UserNotFoundException
     * @throws SigningKeyNotFoundException
     */
    public function handle(GetAccessTokenRequest $getAccessTokenRequest): AccessToken
    {
        $client = $this->clientRepository->ofName($getAccessTokenRequest->clientName);

        if (false === $client->isValidSecret($getAccessTokenRequest->clientSecret)) {
            throw new ClientNameAndSecretMissmatchException();
        }

        $authorizationCode = $this->authorizationCodeRepository->ofCode($getAccessTokenRequest->code);

        if ($authorizationCode->belongsToClient($client) === false) {
            throw new InvalidAuthorizationCodeException('Authorization code does not belong to the client');
        }

        $user = $this->userRepository->ofId($authorizationCode->userId()->toString());
        $signingKey = $this->signingKeyRepository->first();

        $token = $this->jwtToken->execute($user, $signingKey);

        return new AccessToken('Bearer', $token, $user);
    }
}
