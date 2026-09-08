<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\AuthorizationCode\InvalidAuthorizationCodeException;
use Ramsey\Uuid\Uuid;

class AuthorizationCodeInMemoryRepository implements AuthorizationCodeRepository
{
    /**
     * @var AuthorizationCode[]
     */
    private array $authorizationCodes = [];

    public function nextId(): string
    {
        return Uuid::uuid4()->toString();
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

    /**
     * @inheritDoc
     */
    public function ofCode(string $code): AuthorizationCode
    {
        foreach ($this->authorizationCodes as $authorizationCode) {
            if ($authorizationCode->code() === $code) {
                return $authorizationCode;
            }
        }

        throw new InvalidAuthorizationCodeException("Authorization code not found: {$code}");
    }
}
