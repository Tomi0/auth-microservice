<?php

namespace AuthMicroservice\Shared\Infrastructure\Laravel\Domain\Service;

use AuthMicroservice\Shared\Domain\Service\RandomStringGenerator;
use Illuminate\Support\Str;

class RandomStringGeneratorLaravel extends RandomStringGenerator
{

    public function execute(int $length = 16): string
    {
        if ($length < 6)
            throw new \UnexpectedValueException('Value can not be less than 6');

        return Str::random($length);
    }
}
