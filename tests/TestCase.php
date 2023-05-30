<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function beginDatabaseTransaction(): void
    {
        $connection = $this->app->make('em')->getConnection();
        $connection->beginTransaction();

        $this->beforeApplicationDestroyed(function () use ($connection) {
            $connection->rollback();
        });
    }

    protected function getJwtToken(User $user = null): string
    {
        $user = ($user === null) ? entity(User::class)->create() : $user;

        /** @var GenerateJwtToken $configuration */
        $configuration = $this->app->make(GenerateJwtToken::class);
        return 'Bearer ' . $configuration->execute($user);
    }
}
