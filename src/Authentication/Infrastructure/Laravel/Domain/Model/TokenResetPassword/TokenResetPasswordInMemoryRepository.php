<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\TokenResetPassword;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;

class TokenResetPasswordInMemoryRepository implements TokenResetPasswordRepository
{
    /** @var TokenResetPassword[] */
    private array $tokens = [];

    public function persist(TokenResetPassword $tokenResetPassword): void
    {
        $this->tokens[] = $tokenResetPassword;
    }

    public function ofToken(string $tokenResetPassword): TokenResetPassword
    {
        foreach ($this->tokens as $token) {
            if ($token->token() === $tokenResetPassword) {
                return $token;
            }
        }
        throw new TokenResetPasswordNotFoundException();
    }

    public function ofEmail(string $email): TokenResetPassword
    {
        foreach ($this->tokens as $token) {
            if ($token->email() === $email) {
                return $token;
            }
        }
        throw new TokenResetPasswordNotFoundException();
    }
}
