<?php

namespace Tests\src\AuthMicroservice\Authentication\Domain\Service\User;

use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;
use AuthMicroservice\Authentication\Infrastructure\Domain\Model\User\User;
use Illuminate\Database\Eloquent\Model;
use Lcobucci\JWT\Configuration;
use Tests\TestCase;

class GenerateJwtTokenTest extends TestCase
{
    private GenerateJwtToken $generateJwtToken;
    private Model $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateJwtToken = $this->app->make(GenerateJwtToken::class);
        $this->configuration = $this->app->make(Configuration::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = User::factory()->create();
    }

    public function testGeneratedJwtTokenIsValid(): void
    {
        $token = $this->generateJwtToken->execute($this->user);

        /** @var Configuration $configuration */
        $configuration = $this->app->make(Configuration::class);

        $constraints = $configuration->validationConstraints();

        $this->assertTrue($configuration->validator()->validate($configuration->parser()->parse($token), ...$constraints));
    }

}
