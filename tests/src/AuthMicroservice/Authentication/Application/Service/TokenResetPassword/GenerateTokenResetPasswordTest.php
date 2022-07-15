<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Application\Service\TokenResetPassword\GenerateTokenResetPassword;
use AuthMicroservice\Authentication\Application\Service\TokenResetPassword\GenerateTokenResetPasswordRequest;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordGenerated;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Exception;
use Tests\TestCase;

class GenerateTokenResetPasswordTest extends TestCase
{
    private GenerateTokenResetPassword $generateTokenResetPassword;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateTokenResetPassword = $this->app->make(GenerateTokenResetPassword::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->create();
    }

    /**
     * @throws UserNotFoundException
     */
    public function testGenerateTokenResetPassword(): void
    {
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($this->user->email()));

        $this->assertDatabaseHas('token_reset_password', [
            'email' => $this->user->email(),
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function testGenerateNewTokenResetPasswordIfAlreadyExists(): void
    {
        $tokenResetPassword = entity(TokenResetPassword::class)->create(['email' => $this->user->email(), 'token' => 'token_reset_password']);
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($this->user->email()));

        $this->assertInstanceOf(TokenResetPassword::class, $tokenResetPassword);
        $this->assertDatabaseMissing('token_reset_password', [
            'email' => $this->user->email(),
            'token' => 'token_reset_password',
        ]);
        $this->assertDatabaseHas('token_reset_password', [
            'email' => $this->user->email(),
        ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function testFireTokenResetPasswordGenerated(): void
    {
        $this->expectsEvents(TokenResetPasswordGenerated::class);
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($this->user->email()));
    }

    public function testThrowUserNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest('invalidUser@user.test'));
    }
}
