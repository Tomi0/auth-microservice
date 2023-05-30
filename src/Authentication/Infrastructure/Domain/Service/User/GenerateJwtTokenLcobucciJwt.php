<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;

class GenerateJwtTokenLcobucciJwt extends GenerateJwtToken
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function execute(User $user): string
    {
        $now = new DateTimeImmutable();

        return $this->configuration->builder()
            ->issuedBy('auth-microservice')
            ->identifiedBy($user->id())
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
    }
}
