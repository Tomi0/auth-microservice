<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Exception;
use Illuminate\Support\Facades\DB;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutEvents();
        $this->updateUserPassword = $this->app->make(ChangeUserPassword::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->create();
        $this->tokenResetPassword = entity(TokenResetPassword::class)->create([
            'email' => $this->user->email(),
        ]);
        $this->otherUser = entity(User::class)->create([
            'password' => Hash::make('otherUserPassword'),
        ]);
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
        $this->updateUserPassword->handle(new ChangeUserPasswordRequest($this->tokenResetPassword->token(), 'secret', $this->otherUser->email()));

        $user = DB::table('user')->where('email', '=', $this->user->email())->first();
        $this->assertFalse(Hash::check('secret', $user->password));
    }

    /**
     * @throws UserNotFoundException
     * @throws UserHasNotPermissionsException
     * @throws TokenResetPasswordNotFoundException
     */
    public function testUpdateUserPasswordInDB(): void
    {
        $this->updateUserPassword->handle(new ChangeUserPasswordRequest($this->tokenResetPassword->token(), 'secret', $this->user->email()));
        $user = DB::table('user')->where('email', '=', $this->user->email())->first();

        $this->assertTrue(Hash::check('secret', $user->password));
    }

    /**
     * @throws UserNotFoundException
     * @throws TokenResetPasswordNotFoundException
     * @throws UserHasNotPermissionsException
     */
    public function testUpdateCorrectUserPassword(): void
    {
        $this->updateUserPassword->handle(new ChangeUserPasswordRequest($this->tokenResetPassword->token(), 'secret', $this->user->email()));
        $user = DB::table('user')->where('email', '=', $this->user->email())->first();
        $otherUser = DB::table('user')->where('email', '=', $this->otherUser->email())->first();

        $this->assertTrue(Hash::check('secret', $user->password));

        $this->assertFalse(Hash::check('secret', $otherUser->password));
        $this->assertTrue(Hash::check('otherUserPassword', $otherUser->password));
    }

}
