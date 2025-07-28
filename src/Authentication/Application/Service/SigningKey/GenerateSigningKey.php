<?php

namespace Authentication\Application\Service\SigningKey;

use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;

class GenerateSigningKey
{
    private SigningKeyRepository $signingKeyRepository;

    public function __construct(SigningKeyRepository $signingKeyRepository)
    {
        $this->signingKeyRepository = $signingKeyRepository;
    }

    public function handle(GenerateSigningKeyRequest $generateSigningKeyRequest): SigningKey
    {
        try {
            $signingKey = $this->signingKeyRepository->first();
        } catch (SigningKeyNotFoundException $e) {
        }

        if (isset($signingKey)) {
            $this->signingKeyRepository->remove($signingKey);
        }

        $signingKey = new SigningKey(
            $this->signingKeyRepository->nextId()
        );

        $this->signingKeyRepository->persist($signingKey);

        return $signingKey;
    }
}
