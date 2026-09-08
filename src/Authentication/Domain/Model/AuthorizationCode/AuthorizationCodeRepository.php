<?php

namespace Authentication\Domain\Model\AuthorizationCode;

interface AuthorizationCodeRepository
{

    public function nextId(): string;

    public function persist(AuthorizationCode $authorizationCode): void;

    /**
     * @throws InvalidAuthorizationCodeException
     */
    public function ofCode(string $code): AuthorizationCode;
}
