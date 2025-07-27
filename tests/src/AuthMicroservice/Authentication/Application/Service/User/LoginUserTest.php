<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\Client\HostNotAuthorized;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\User\UserLoggedIn;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Authentication\Domain\Service\User\GenerateJwtToken;
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
use Shared\Domain\Service\GetConfigItem;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    private User $user;
    private LoginUser $loginUser;
    private Client $authorizedHost;
    private UserRepository $userRepository;
    private ClientRepository $autorizedHostRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->autorizedHostRepository = $this->app->make(ClientRepository::class);
        $this->loginUser = new LoginUser(
            $this->userRepository,
            $this->autorizedHostRepository,
            $this->app->make(GenerateJwtToken::class),
            $this->app->make(GetConfigItem::class),
            $this->signingKeyRepository,
            $this->app->make(CheckPasswordHash::class),
        );
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->make([
            'password' => Hash::make('password')
        ]);
        $this->authorizedHost = entity(Client::class)->make([
            'compra.tomibuenalacid.es',
        ]);
        $this->userRepository->persist($this->user);
        $this->autorizedHostRepository->persist($this->authorizedHost);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function testReturnValueIsString(): void
    {
        $value = $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password', $this->authorizedHost->redirectUrl()));

        $this->assertIsString($value);
    }

    /**
     * @throws HostNotAuthorized
     * @throws InvalidCredentialsException
     */
    public function testPublicUserLoggedIn(): void
    {
        $this->assertEventPublished(UserLoggedIn::class);
        $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password', $this->authorizedHost->redirectUrl()));
    }

    public function testThrowInvalidCredentialsWhenWrongEmail(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->loginUser->handle(new LoginUserRequest('asdf', 'password', $this->authorizedHost->redirectUrl()));
    }

    public function testThrowInvalidCredentialsWhenWrongPassword(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'incorrect password', $this->authorizedHost->redirectUrl()));
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
        $result = $this->loginUser->handle(new LoginUserRequest($this->user->email(), 'password', $this->authorizedHost->redirectUrl()));

        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($result);

        $validator = new Validator();

        $publicKey = $this->signingKeyRepository->first()->publicKey();
        $this->assertTrue($validator->validate($token, new SignedWith(new Sha256(), InMemory::plainText($publicKey))));
    }
}
