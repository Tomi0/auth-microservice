<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AuthorizationCodeInMemoryRepository implements AuthorizationCodeRepository
{

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
