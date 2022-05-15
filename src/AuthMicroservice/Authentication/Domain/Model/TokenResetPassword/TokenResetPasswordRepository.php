<?php

namespace AuthMicroservice\Authentication\Domain\Model\TokenResetPassword;

interface TokenResetPasswordRepository
{

    public function persist(TokenResetPassword $tokenResetPassword): void;
}
