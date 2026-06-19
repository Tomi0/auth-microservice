<?php

namespace Authentication\Application\Service\Client;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Service\User\EncodePassword;
use Shared\Domain\Service\RandomStringGenerator;

class CreateClient
{
    private ClientRepository $clientRepository;
    private RandomStringGenerator $randomStringGenerator;
    private EncodePassword $encodePassword;
    private SigningKeyRepository $signingKeyRepository;

    public function __construct(ClientRepository $clientRepository,
                                RandomStringGenerator $randomStringGenerator,
                                EncodePassword $encodePassword,
                                SigningKeyRepository $signingKeyRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->encodePassword = $encodePassword;
        $this->signingKeyRepository = $signingKeyRepository;
    }

    public function handle(CreateClientRequest $createClientRequest): string
    {
        $clientSecret = $this->randomStringGenerator->execute(32);
        $siginngKey = new SigningKey($this->signingKeyRepository->nextId());

        $client = new Client(
            $this->clientRepository->nextId(),
            $createClientRequest->name,
            $this->encodePassword->execute($clientSecret),
            $createClientRequest->redirectUrl,
            $siginngKey->id()
        );

        $this->clientRepository->persist($client);

        return $clientSecret;
    }
}
