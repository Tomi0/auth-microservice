<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AuthorizationCodeInMemoryRepository implements AuthorizationCodeRepository
{
    /**
     * @var AuthorizationCode[]
     */
    private array $authorizationCodes = [];

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function persist(AuthorizationCode $authorizationCode): void
    {
        $this->authorizationCodes[] = $authorizationCode;
    }

    /**
     * @return AuthorizationCode[]
     */
    public function authorizationCodes(): array
    {
        return $this->authorizationCodes;
    }
}
