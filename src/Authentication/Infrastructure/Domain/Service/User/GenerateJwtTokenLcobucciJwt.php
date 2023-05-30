<?php

namespace Authentication\Infrastructure\Domain\Service\User;

use DateTimeImmutable;
use Illuminate\Support\Facades\Storage;
use Lcobucci\JWT\Configuration;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use \Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Ramsey\Uuid\Uuid;

class GenerateJwtTokenLcobucciJwt extends GenerateJwtToken
{
    public function execute(User $user): string
    {
        $tokenBuilder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $algorithm = new Sha256();
        $signingKey = InMemory::file(config('filesystems.disks.jwt_signing_keys.root') . '/' . config('jwt.jwt_private_key_filename'));
        $now = new DateTimeImmutable();

        return $tokenBuilder->issuedBy('auth-microservice')
            ->identifiedBy(Uuid::uuid4())
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('user_id', $user->id())
            ->withClaim('user_email', $user->email())
            ->getToken($algorithm, $signingKey)
            ->toString();
    }
}
