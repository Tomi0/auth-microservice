<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\EncodePassword;
use Exception;
use Illuminate\Support\Facades\Hash;
use Authentication\Application\Service\User\ChangeUserPassword;
use Authentication\Application\Service\User\ChangeUserPasswordRequest;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserHasNotPermissionsException;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserPasswordChanged;
use Tests\TestCase;

class ChangeUserPasswordTest extends TestCase
{
    private ChangeUserPassword $updateUserPassword;
    private User $user;
    private TokenResetPassword $tokenResetPassword;
    private User $otherUser;
    private UserRepository $userRepository;
    private $tokenResetPasswordRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutEvents();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->tokenResetPasswordRepository = $this->app->make(TokenResetPasswordRepository::class);
        $this->updateUserPassword = new ChangeUserPassword(
            $this->userRepository,
            $this->tokenResetPasswordRepository,
            $this->app->make(EncodePassword::class),
        );
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->make();
        $this->tokenResetPassword = entity(TokenResetPassword::class)->make([
            'email' => $this->user->email(),
        ]);
        $this->otherUser = entity(User::class)->make([
            'password' => Hash::make('otherUserPassword'),
        ]);

        $this->userRepository->persist($this->user);
        $this->userRepository->persist($this->otherUser);
        $this->tokenResetPasswordRepository->persist($this->tokenResetPassword);

    }

    /**
     * @throws UserNotFoundException
     * @throws TokenResetPasswordNotFoundException
     * @throws UserHasNotPermissionsException
     * @throws Exception
     */
    public function testFireUserPasswordUpdated(): void
    {
        $this->assertEventPublished(UserPasswordChanged::class);
        $this->updateUserPassword->handle(new ChangeUserPasswordRequest($this->tokenResetPassword->token(), 'secret', $this->user->email()));
    }

    /**
     * @throws UserNotFoundException
     * @throws UserHasNotPermissionsException
     */
    public function testThrowTokenResetPasswordNotFoundExceptionWhenWrongToken(): void
    {
        $this->expectException(TokenResetPasswordNotFoundException::class);
        $this->updateUserPassword->handle(new ChangeUserPasswordRequest('invalid token!!', 'secret', $this->user->email()));
    }

    /**
     * @throws UserNotFoundException
     * @throws TokenResetPasswordNotFoundException
     */
    public function testUserPasswordIsNotUpdatedWhenUserHasNotPermissionsExceptionThrown(): void
    {
        $this->expectException(UserHasNotPermissionsException::class);
        $newPassword = 'secret';
        $this->updateUserPassword->handle(
            new ChangeUserPasswordRequest(
                $this->tokenResetPassword->token(),
                $newPassword,
                $this->otherUser->email()
            )
        );


        $this->assertFalse(Hash::check($newPassword, $this->otherUser->password()));
    }

    /**
     * @throws UserNotFoundException
     * @throws UserHasNotPermissionsException
     * @throws TokenResetPasswordNotFoundException
     */
    public function testUpdateUserPasswordInDB(): void
    {
        $newPassword = 'secret';

        $this->updateUserPassword->handle(
            new ChangeUserPasswordRequest(
                $this->tokenResetPassword->token(),
                $newPassword,
                $this->user->email()
            )
        );


        $this->assertTrue(Hash::check($newPassword, $this->user->password()));
    }

    /**
     * @throws UserNotFoundException
     * @throws TokenResetPasswordNotFoundException
     * @throws UserHasNotPermissionsException
     */
    public function testUpdateCorrectUserPassword(): void
    {
        $newPassword = 'secret';
        $this->updateUserPassword->handle(new ChangeUserPasswordRequest($this->tokenResetPassword->token(), $newPassword, $this->user->email()));

        $this->assertTrue(Hash::check($newPassword, $this->user->password()));

        $this->assertFalse(Hash::check($newPassword, $this->otherUser->password()));
        $this->assertTrue(Hash::check('otherUserPassword', $this->otherUser->password()));
    }

}
