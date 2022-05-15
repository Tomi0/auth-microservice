<?php

namespace AuthMicroservice\Shared\Domain\Service;

abstract class RandomStringGenerator
{
    public abstract function execute(int $length = 16): string;
}
