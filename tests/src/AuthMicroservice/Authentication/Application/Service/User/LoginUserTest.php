<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Configuration;
use Authentication\Application\Service\User\LoginUser;
use Authentication\Application\Service\User\LoginUserRequest;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\User;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    private User $user;
    private LoginUser $loginUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loginUser = $this->app->make(LoginUser::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->create([
            'password' => Hash::make('password')
        ]);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testReturnValueIsString(): void
    {
        $value = $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password'));

        $this->assertIsString($value);
    }

    public function testThrowInvalidCredentialsWhenWrongEmail(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->loginUser->handle(new LoginUserRequest('asdf', 'password'));
    }

    public function testThrowInvalidCredentialsWhenWrongPassword(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'incorrect password'));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testValidJwtToken(): void
    {
        $result = $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password'));

        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($result);

        $validator = new Validator();

        $this->assertTrue($validator->validate($token, new SignedWith(new Sha256(), InMemory::file(config('filesystems.disks.jwt_signing_keys.root') . '/' . config('jwt.jwt_public_key_filename')))));
    }
}
