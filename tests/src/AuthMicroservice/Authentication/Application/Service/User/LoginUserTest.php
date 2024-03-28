<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;
use Authentication\Domain\Model\AuthorizedHost\HostNotAuthorized;
use Authentication\Domain\Model\User\UserLoggedIn;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
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
    private AuthorizedHost $authorizedHost;

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
        $this->authorizedHost = entity(AuthorizedHost::class)->create([
            'compra.tomibuenalacid.es',
        ]);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testReturnValueIsString(): void
    {
        $value = $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password', $this->authorizedHost->hostName()));

        $this->assertIsString($value);
    }

    /**
     * @throws HostNotAuthorized
     * @throws InvalidCredentialsException
     */
    public function testPublicUserLoggedIn(): void
    {
        $this->assertEventPublished(UserLoggedIn::class);
        $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password', $this->authorizedHost->hostName()));
    }

    public function testThrowInvalidCredentialsWhenWrongEmail(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->loginUser->handle(new LoginUserRequest('asdf', 'password', $this->authorizedHost->hostName()));
    }

    public function testThrowInvalidCredentialsWhenWrongPassword(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'incorrect password', $this->authorizedHost->hostName()));
    }

    public function testThrowHostNotAuthorized(): void
    {
        Config::set('auth.enable_authorized_host', true);
        $this->expectException(HostNotAuthorized::class);
        $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'incorrect password', 'test.com'));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testValidJwtToken(): void
    {
        $result = $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password', $this->authorizedHost->hostName()));

        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($result);

        $validator = new Validator();

        $this->assertTrue($validator->validate($token, new SignedWith(new Sha256(), InMemory::plainText($this->createOrGetSigningKey()->publicKey()))));
    }
}
