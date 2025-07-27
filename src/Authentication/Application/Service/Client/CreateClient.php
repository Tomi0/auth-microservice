<?php

namespace Authentication\Application\Service\Client;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Service\User\EncodePassword;
use Shared\Domain\Service\RandomStringGenerator;

class CreateClient
{
    private ClientRepository $clientRepository;
    private RandomStringGenerator $randomStringGenerator;
    private EncodePassword $encodePassword;

    public function __construct(ClientRepository $clientRepository,
                                RandomStringGenerator $randomStringGenerator,
                                EncodePassword $encodePassword)
    {
        $this->clientRepository = $clientRepository;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->encodePassword = $encodePassword;
    }

    public function handle(CreateClientRequest $createClientRequest): string
    {
        $clientSecret = $this->randomStringGenerator->execute();

        $client = new Client(
            $this->clientRepository->nextId(),
            $createClientRequest->name,
            $this->encodePassword->execute($clientSecret),
            $createClientRequest->redirectUrl
        );

        $this->clientRepository->persist($client);

        return $clientSecret;
    }
}
