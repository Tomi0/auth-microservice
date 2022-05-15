<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Application\Service\TokenResetPassword\GetTokenResetPassword;
use AuthMicroservice\Authentication\Application\Service\TokenResetPassword\GetTokenResetPasswordRequest;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRequested;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Tests\TestCase;

class GetTokenResetPasswordTest extends TestCase
{
    private GetTokenResetPassword $getTokenResetPassword;
    private User $userAdmin;
    private User $user;
    private User $user1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getTokenResetPassword = $this->app->make(GetTokenResetPassword::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->userAdmin = entity(User::class)->create([
            'admin' => 1,
        ]);
        $this->user = entity(User::class)->create([
            'admin' => 0,
        ]);
        $this->user1 = entity(User::class)->create([
            'admin' => 0,
        ]);
    }

    public function testAdminGetsResetPasswordToken(): void
    {
        $tokenResetPassword = $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest($this->user->email(), $this->userAdmin));

        $this->assertInstanceOf(TokenResetPassword::class, $tokenResetPassword);
        $this->assertDatabaseHas('token_reset_password', [
            'email' => $this->user->email(),
            'token' => $tokenResetPassword->token(),
        ]);
    }

    public function testFireTokenResetPasswordRequested(): void
    {
        $this->expectsEvents(TokenResetPasswordRequested::class);
        $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest($this->user->email(), $this->userAdmin));
    }

    public function testUserIsAbleToGetHimselfPasswordResetToken(): void
    {
        $tokenResetPassword = $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest($this->user->email(), $this->user));

        $this->assertInstanceOf(TokenResetPassword::class, $tokenResetPassword);
        $this->assertDatabaseHas('token_reset_password', [
            'email' => $this->user->email(),
            'token' => $tokenResetPassword->token(),
        ]);
    }

    public function testUserIsNotAbleToGetTokenResetPasswordFromOtherUser(): void
    {
        $this->expectException(UserHasNotPermissionsException::class);
        $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest($this->user->email(), $this->user1));
    }

    public function testNotFireTokenResetPasswordRequested(): void
    {
        $this->doesntExpectEvents(TokenResetPasswordRequested::class);
        $this->expectException(UserHasNotPermissionsException::class);
        $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest($this->user->email(), $this->user1));
    }

    public function testThrowUserNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest('invalidUser@user.test', $this->userAdmin));
    }
}
