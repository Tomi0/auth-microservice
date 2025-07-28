<?php

namespace Tests\app\UI\Console\Commands;

use Authentication\Application\Service\Client\CreateClient;
use Authentication\Application\Service\Client\CreateClientRequest;
use Mockery\MockInterface;
use Tests\TestCase;

class GenerateSigningKeyCommandTest extends TestCase
{

    public function testCallServiceWithExpectedData(): void
    {
        $this->mock(CreateClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')
                ->once()
                ->withArgs(fn($arg) => $arg == new CreateClientRequest('test-client', 'https://example.com/callback'))
                ->andReturn('random-client-secret');
        });

        $this->artisan('client:create')
            ->expectsQuestion('Enter the client name', 'Test Client')
            ->expectsQuestion('Enter the client redirect URI', 'https://example.com/callback')
            ->expectsOutput('Client Secret (one time display): random-client-secret');

    }

}
