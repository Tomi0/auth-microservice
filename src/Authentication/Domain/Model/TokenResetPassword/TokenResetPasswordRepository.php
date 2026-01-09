<?php

namespace Authentication\Domain\Model\TokenResetPassword;

use Ramsey\Uuid\UuidInterface;

interface TokenResetPasswordRepository
{

    public function persist(TokenResetPassword $tokenResetPassword): void;

    /**
     * @throws TokenResetPasswordNotFoundException
     */
    public function ofToken(string $tokenResetPassword): TokenResetPassword;

    /**
     * @throws TokenResetPasswordNotFoundException
     */
    public function ofEmail(string $email): TokenResetPassword;

    public function remove(TokenResetPassword $tokenResetPassword): void;

    public function nextId(): UuidInterface;
}
