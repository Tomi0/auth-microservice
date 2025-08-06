<?php

namespace Authentication\Domain\Model\AuthorizationCode;

use Ramsey\Uuid\UuidInterface;

interface AuthorizationCodeRepository
{

    public function nextId(): UuidInterface;

    public function persist(AuthorizationCode $authorizationCode): void;
}
