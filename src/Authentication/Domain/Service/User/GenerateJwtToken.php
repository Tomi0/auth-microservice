<?php

namespace Authentication\Domain\Service\User;

abstract class GenerateJwtToken
{
    public abstract function execute(object $user): string;
}
