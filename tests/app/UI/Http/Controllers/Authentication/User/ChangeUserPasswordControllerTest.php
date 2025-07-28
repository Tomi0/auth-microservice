<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;


use Authentication\Application\Service\User\ChangeUserPassword;
use Authentication\Application\Service\User\ChangeUserPasswordRequest;
use Mockery\MockInterface;
use Tests\TestCase;

class ChangeUserPasswordControllerTest extends TestCase
{
    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/oauth/user/change-password', []);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $this->mock(ChangeUserPassword::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->withArgs(function ($arg) {
                return $arg == (new ChangeUserPasswordRequest('token_reset_password', 'secret', 'tomi0@test.com'));
            })->once();
        });
        $request = $this->postJson('/oauth/user/change-password', [
            'email' => 'tomi0@test.com',
            'password' => 'secret',
            'token' => 'token_reset_password'
        ]);

        $request->assertStatus(200);
    }
}
