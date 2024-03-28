<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\SigingKey;

use Authentication\Application\Service\SigningKey\GenerateSigningKey;
use Authentication\Application\Service\SigningKey\GenerateSigningKeyRequest;
use Authentication\Domain\Model\SigningKey\SigingKeyCreated;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Mockery\MockInterface;
use Tests\TestCase;

class GenerateSigingKeyTest extends TestCase
{
    private GenerateSigningKey $generateSigningKey;

    public function testThrowSigingKeyCreated(): void
    {
        $this->assertEventPublished(SigingKeyCreated::class);
        $this->mock(SigningKeyRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('first')->once();
            $mock->shouldReceive('persist')->once();
            $mock->shouldReceive('nextId')->once();
            $mock->shouldReceive('remove')->once();
        });

        $this->generateSigningKey = app()->make(GenerateSigningKey::class);
        $this->generateSigningKey->handle(new GenerateSigningKeyRequest());
    }

    public function testReturnSigningKey(): void
    {
        $this->mock(SigningKeyRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('first')->once();
            $mock->shouldReceive('persist')->once();
            $mock->shouldReceive('nextId')->once();
            $mock->shouldReceive('remove')->once();
        });

        $this->generateSigningKey = app()->make(GenerateSigningKey::class);
        $result = $this->generateSigningKey->handle(new GenerateSigningKeyRequest());

        $this->assertInstanceOf(SigningKey::class, $result);
    }

    public function testPersistCreateSigingKey(): void
    {
        $this->mock(SigningKeyRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('persist')->withArgs(fn($arg) => $arg instanceof SigningKey)->once();
            $mock->shouldReceive('nextId')->once();
            $mock->shouldReceive('first')->once();
            $mock->shouldReceive('remove')->once();
        });

        $this->generateSigningKey = app()->make(GenerateSigningKey::class);
        $result = $this->generateSigningKey->handle(new GenerateSigningKeyRequest());

        $this->assertInstanceOf(SigningKey::class, $result);
    }
}
