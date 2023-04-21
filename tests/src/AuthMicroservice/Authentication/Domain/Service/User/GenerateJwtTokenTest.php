<?php

namespace Tests\src\AuthMicroservice\Authentication\Domain\Service\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;
use Lcobucci\JWT\Configuration;
use Tests\TestCase;

class GenerateJwtTokenTest extends TestCase
{
    private GenerateJwtToken $generateJwtToken;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateJwtToken = $this->app->make(GenerateJwtToken::class);
        $this->configuration = $this->app->make(Configuration::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->create();
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
