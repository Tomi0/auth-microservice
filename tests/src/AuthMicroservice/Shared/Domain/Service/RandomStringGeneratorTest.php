<?php

namespace Tests\src\AuthMicroservice\Shared\Domain\Service;

use Shared\Domain\Service\RandomStringGenerator;
use Tests\TestCase;
use UnexpectedValueException;

class RandomStringGeneratorTest extends TestCase
{
    private RandomStringGenerator $randomStringGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->randomStringGenerator = $this->app->make(RandomStringGenerator::class);
    }

    public function testResultIsString(): void
    {
        $result = $this->randomStringGenerator->execute();
        $this->assertIsString($result);
    }

    public function testReturnStringWithExactLength(): void
    {
        $length = 6;
        $result = $this->randomStringGenerator->execute($length);
        $this->assertSame($length, strlen($result));
    }

    public function testMinLengthIs6(): void
    {
        $length = 5;
        $this->expectException(UnexpectedValueException::class);
        $this->randomStringGenerator->execute($length);
    }

    public function testResultsAreRandomsStrings(): void
    {
        $length = 6;
        $randomArrayLength = 100;

        $arrayStringRandom = [];

        for ($i = 0; $i < $randomArrayLength; $i++) {
            $arrayStringRandom[] = $this->randomStringGenerator->execute($length);
        }

        $this->assertSame($randomArrayLength, count(array_unique($arrayStringRandom)));
    }
}
