<?php

namespace Tests\app\UI\Http\Controllers\Authentication\TokenResetPassword;

use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserRepository;
use Tests\TestCase;

class GenerateTokenResetPasswordControllerTest extends TestCase
{
    private User $userToResetPassword;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->userToResetPassword = entity(User::class)->make([
            'admin' => 0,
        ]);
        $this->userRepository->persist($this->userToResetPassword);
        $this->app->instance(UserRepository::class, $this->userRepository);
    }

    public function testRouteNotWorkingIfUserNotLogged(): void
    {
        $httpRequest = $this->postJson('/oauth/token-reset-password', [
            'email' => $this->userToResetPassword->email(),
        ]);

        $this->assertSame(200, $httpRequest->getStatusCode());
    }

    public function testRouteWorksIfUserLogged(): void
    {
        $httpRequest = $this->postJson('/oauth/token-reset-password', [
            'email' => $this->userToResetPassword->email(),
        ], [
            'Authorization' => $this->getJwtToken($this->userToResetPassword),
        ]);

        $this->assertSame(200, $httpRequest->getStatusCode());
    }
}
