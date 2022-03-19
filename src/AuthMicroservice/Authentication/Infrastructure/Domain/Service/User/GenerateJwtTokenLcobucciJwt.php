<?php

namespace AuthMicroservice\Authentication\Infrastructure\Domain\Service\User;

use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;

class GenerateJwtTokenLcobucciJwt extends GenerateJwtToken
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function execute(object $user): string
    {
        $now = new DateTimeImmutable();

        return $this->configuration->builder()
            ->issuedBy('auth-microservice')
            ->identifiedBy($user['id'])
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
    }
}
