<?php

namespace Authentication\Domain\Service\User;

abstract class EncodePassword
{
    public abstract function execute(string $password): string;
}
