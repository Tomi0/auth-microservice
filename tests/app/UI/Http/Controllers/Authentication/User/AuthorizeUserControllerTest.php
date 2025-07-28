<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use Authentication\Application\Service\User\AuthorizeUser;
use Authentication\Application\Service\User\AuthorizeUserRequest;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthorizeUserControllerTest extends TestCase
{


    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/oauth/authorize', [
            'email' => null,
            'password' => null,
            'clientName' => null,
            'redirectUrl' => 'https://example.com/callback',
        ]);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $this->mock(AuthorizeUser::class, function (MockInterface $mock) {
            $authorizationCode = entity(AuthorizationCode::class)->make();
            $mock->shouldReceive('handle')
                ->once()
                ->withArgs(function ($request) {
                    return $request == new AuthorizeUserRequest(
                            'test@example.com',
                            'secret',
                            'client-test',
                            'https://example.com/callback'
                        );
                })
                ->andReturn($authorizationCode);
        });
        $request = $this->postJson('/oauth/authorize', [
            'email' => 'test@example.com',
            'password' => 'secret',
            'clientName' => 'client-test',
            'redirectUrl' => 'https://example.com/callback',
        ]);

        $request->assertStatus(200);
    }

    public function testInvalidCredentials(): void
    {
        $this->mock(AuthorizeUser::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')
                ->once()
                ->andThrow(InvalidCredentialsException::class);
        });
        $request = $this->postJson('/oauth/authorize', [
            'email' => 'user',
            'password' => 'invalid password',
            'clientName' => 'client-test',
            'redirectUrl' => 'https://example.com/callback',
        ]);

        $request->assertStatus(401);
    }
}
