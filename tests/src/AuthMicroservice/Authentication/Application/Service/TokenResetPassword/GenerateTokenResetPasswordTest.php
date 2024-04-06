<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\UserRepository;
use Exception;
use Authentication\Application\Service\TokenResetPassword\GenerateTokenResetPassword;
use Authentication\Application\Service\TokenResetPassword\GenerateTokenResetPasswordRequest;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordGenerated;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserNotFoundException;
use Shared\Domain\Model\DomainEvent;
use Shared\Domain\Service\RandomStringGenerator;
use Tests\TestCase;

class GenerateTokenResetPasswordTest extends TestCase
{
    private GenerateTokenResetPassword $generateTokenResetPassword;
    private User $user;
    private UserRepository $userRepository;
    private TokenResetPasswordRepository $tokenResetPasswordRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->tokenResetPasswordRepository = $this->app->make(TokenResetPasswordRepository::class);
        $this->generateTokenResetPassword = new GenerateTokenResetPassword(
            $this->tokenResetPasswordRepository,
            $this->userRepository,
            $this->app->make(RandomStringGenerator::class),
        );
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->make();
        $this->userRepository->persist($this->user);
    }

    /**
     * @throws UserNotFoundException
     */
    public function testGenerateTokenResetPassword(): void
    {
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($this->user->email()));

        $this->assertInstanceOf(TokenResetPassword::class, $this->tokenResetPasswordRepository->ofEmail($this->user->email()));
    }

    /**
     * @throws UserNotFoundException
     */
    public function testGenerateNewTokenResetPasswordIfAlreadyExists(): void
    {
        $tokenResetPassword = entity(TokenResetPassword::class)->make(['email' => $this->user->email(), 'token' => 'token_reset_password']);
        $this->tokenResetPasswordRepository->persist($tokenResetPassword);
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($this->user->email()));

        $this->assertInstanceOf(TokenResetPassword::class, $tokenResetPassword);

        $token = $this->tokenResetPasswordRepository->ofEmail($this->user->email());
        $this->assertNotEquals('token_reset_password', $token->token());
    }

    /**
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function testFireTokenResetPasswordGenerated(): void
    {
        $this->assertEventPublished(TokenResetPasswordGenerated::class);
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($this->user->email()));
    }

    public function testThrowUserNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest('invalidUser@user.test'));
    }
}
