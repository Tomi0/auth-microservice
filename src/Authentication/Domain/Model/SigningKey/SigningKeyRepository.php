<?php

namespace Authentication\Domain\Model\SigningKey;

interface SigningKeyRepository
{

    public function nextId(): string;

    public function persist(SigningKey $sigingKey);

    /**
     * @throws SigningKeyNotFoundException
     */
    public function first(): SigningKey;

    /**
     * @throws SigningKeyNotFoundException
     */
    public function ofId(string $id): SigningKey;

    public function remove(SigningKey $signingKey): void;
}
