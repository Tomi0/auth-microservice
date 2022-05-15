<?php

namespace Tests\app\UI\Http\Controllers\Authentication\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use Tests\TestCase;

class GetTokenResetPasswordControllerTest extends TestCase
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
        $httpRequest = $this->postJson('/backoffice-shared/token-reset-password', [
            'email' => $this->userToResetPassword->email(),
        ]);

        $this->assertSame(401, $httpRequest->getStatusCode());
    }

    public function testRouteWorksIfUserLogged(): void
    {
        $httpRequest = $this->postJson('/backoffice-shared/token-reset-password', [
            'email' => $this->userToResetPassword->email(),
        ], [
            'Authorization' => $this->getJwtToken($this->userToResetPassword),
        ]);

        $this->assertSame(200, $httpRequest->getStatusCode());
    }
}
