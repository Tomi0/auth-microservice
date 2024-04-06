<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\SigningKey;

use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Ramsey\Uuid\Uuid;

class SigningKeyInMemoryRepository implements SigningKeyRepository
{
    /**
     * @var SigningKey[]
     */
    private array $signingKeys = [];

    public function nextId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function persist(SigningKey $sigingKey)
    {
        $this->signingKeys[] = $sigingKey;
    }

    public function first(): SigningKey
    {
        $signingKey = $this->signingKeys[0] ?? null;
        if ($signingKey === null) {
            throw new SigningKeyNotFoundException();
        }
        return $signingKey;
    }

    public function remove(SigningKey $signingKey): void
    {
        foreach ($this->signingKeys as $key => $value) {
            if ($value->id() === $signingKey->id()) {
                unset($this->signingKeys[$key]);
            }
        }
    }
}
