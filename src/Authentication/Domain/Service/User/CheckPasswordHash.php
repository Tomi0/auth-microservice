<?php

namespace Authentication\Domain\Service\User;

abstract class CheckPasswordHash
{
    public abstract function execute(string $password, string $hash): bool;
}
