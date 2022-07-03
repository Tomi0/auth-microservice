<?php

namespace AuthMicroservice\Authentication\Domain\Model\TokenResetPassword;

interface TokenResetPasswordRepository
{

    public function persist(TokenResetPassword $tokenResetPassword): void;

    /**
     * @throws TokenResetPasswordNotFoundException
     */
    public function ofToken(string $tokenResetPassword): TokenResetPassword;
}
