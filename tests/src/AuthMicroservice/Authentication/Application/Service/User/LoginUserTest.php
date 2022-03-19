<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Application\Service\User\LoginUser;
use AuthMicroservice\Authentication\Application\Service\User\LoginUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\InvalidCredentialsException;
use AuthMicroservice\Authentication\Infrastructure\Domain\Model\User\User;
use Illuminate\Database\Eloquent\Model;
use Lcobucci\JWT\Configuration;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    private Model $user;
    private LoginUser $loginUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loginUser = $this->app->make(LoginUser::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = User::factory()->create();
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testReturnValueIsString(): void
    {
        $value = $this->loginUser->handle(new LoginUserRequest($this->user['email'], 'password'));

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
        $this->loginUser->handle(new LoginUserRequest($this->user['email'], 'incorrect password'));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testValidJwtToken(): void
    {
        $result = $this->loginUser->handle(new LoginUserRequest($this->user['email'], 'password'));

        /** @var Configuration $configuration */
        $configuration = $this->app->make(Configuration::class);

        $constraints = $configuration->validationConstraints();

        $this->assertTrue($configuration->validator()->validate($configuration->parser()->parse($result), ...$constraints));
    }
}
