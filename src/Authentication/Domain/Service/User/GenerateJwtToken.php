<?php

namespace Authentication\Domain\Service\User;

use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\User\User;

abstract class GenerateJwtToken
{
    public abstract function execute(User $user, SigningKey $signingKey): string;
}
