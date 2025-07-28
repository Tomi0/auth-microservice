<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Ramsey\Uuid\Uuid;

class GenerateJwtTokenLcobucciJwt extends GenerateJwtToken
{
    public function execute(User $user, SigningKey $signingKey): string
    {
        $tokenBuilder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $algorithm = new Sha256();

        $jwtSigningKey = InMemory::plainText($signingKey->privateKey());
        $now = new DateTimeImmutable();

        return $tokenBuilder->issuedBy('auth-microservice')
            ->identifiedBy(Uuid::uuid4())
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('user_id', $user->id())
            ->withClaim('user_email', $user->email())
            ->getToken($algorithm, $jwtSigningKey)
            ->toString();
    }
}
