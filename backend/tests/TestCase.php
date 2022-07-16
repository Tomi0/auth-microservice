<?php

namespace Tests;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Service\User\GenerateJwtToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    protected function getJwtToken(User $user = null): string
    {
        $user = ($user === null) ? entity(User::class)->create() : $user;

        /** @var GenerateJwtToken $configuration */
        $configuration = $this->app->make(GenerateJwtToken::class);
        return 'Bearer ' . $configuration->execute($user);
    }
}
