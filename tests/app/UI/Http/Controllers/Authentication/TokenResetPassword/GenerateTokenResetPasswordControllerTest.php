<?php

namespace Tests\app\UI\Http\Controllers\Authentication\TokenResetPassword;

use Authentication\Domain\Model\User\User;
use Tests\TestCase;

class GenerateTokenResetPasswordControllerTest extends TestCase
{
    private User $userToResetPassword;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userToResetPassword = entity(User::class)->create([
            'admin' => 0,
        ]);
    }

    public function testRouteNotWorkingIfUserNotLogged(): void
    {
        $httpRequest = $this->postJson('/auth/token-reset-password', [
            'email' => $this->userToResetPassword->email(),
        ]);

        $this->assertSame(200, $httpRequest->getStatusCode());
    }

    public function testRouteWorksIfUserLogged(): void
    {
        $httpRequest = $this->postJson('/auth/token-reset-password', [
            'email' => $this->userToResetPassword->email(),
        ], [
            'Authorization' => $this->getJwtToken($this->userToResetPassword),
        ]);

        $this->assertSame(200, $httpRequest->getStatusCode());
    }
}
