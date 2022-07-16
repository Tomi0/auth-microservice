<?php

namespace AuthMicroservice\Authentication\Domain\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;

abstract class GenerateJwtToken
{
    public abstract function execute(User $user): string;
}
