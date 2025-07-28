<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Authentication\Application\Service\User\AuthorizeUser;
use Authentication\Application\Service\User\AuthorizeUserRequest;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\AuthorizationCode\InvalidRedirectUrlException;
use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserAuthorized;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\CheckPasswordHash;
use DateTimeImmutable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Shared\Domain\Service\GetConfigItem;
use Shared\Domain\Service\RandomStringGenerator;
use Tests\TestCase;

class AuthorizeUserTest extends TestCase
{
    private User $user;
    private AuthorizeUser $authorizeUser;
    private Client $client;
    private UserRepository $userRepository;
    private ClientRepository $clientRepository;
    private AuthorizationCodeRepository $authorizationCodeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->clientRepository = $this->app->make(ClientRepository::class);
        $this->authorizationCodeRepository = $this->app->make(AuthorizationCodeRepository::class);
        $this->authorizeUser = new AuthorizeUser(
            $this->userRepository,
            $this->clientRepository,
            $this->authorizationCodeRepository,
            $this->app->make(RandomStringGenerator::class),
            $this->app->make(GetConfigItem::class),
            $this->app->make(CheckPasswordHash::class),
        );
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->make([
            'password' => Hash::make('password')
        ]);
        $this->client = entity(Client::class)->make([
            'name' => 'test-987654',
            'redirectUrl' => 'https://example.com/callback',
        ]);
        $this->userRepository->persist($this->user);
        $this->clientRepository->persist($this->client);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws InvalidRedirectUrlException
     * @throws ClientNotFoundException
     */
    public function testReturnAuthorizationCode(): void
    {
        $result = $this->authorizeUser->handle(new AuthorizeUserRequest(
            $this->user->email(),
            'password',
            $this->client->name(),
            $this->client->redirectUrl()
        ));

        $this->assertInstanceOf(AuthorizationCode::class, $result);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws InvalidRedirectUrlException
     * @throws ClientNotFoundException
     */
    public function testPublishUserAuthorized(): void
    {
        $this->assertEventPublished(UserAuthorized::class);
        $this->authorizeUser->handle(new AuthorizeUserRequest(
            $this->user->email(),
            'password',
            $this->client->name(),
            $this->client->redirectUrl()
        ));
    }

    /**
     * @throws InvalidRedirectUrlException
     * @throws ClientNotFoundException
     */
    public function testThrowInvalidCredentialsWhenWrongEmail(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->authorizeUser->handle(new AuthorizeUserRequest(
            'wrong@email.com',
            'password',
            $this->client->name(),
            $this->client->redirectUrl()
        ));
    }

    /**
     * @throws InvalidRedirectUrlException
     * @throws ClientNotFoundException
     */
    public function testThrowInvalidCredentialsWhenWrongPassword(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->authorizeUser->handle(new AuthorizeUserRequest(
            $this->user->email(),
            'wrong password',
            $this->client->name(),
            $this->client->redirectUrl()
        ));
    }

    /**
     * @throws InvalidCredentialsException
     * @throws ClientNotFoundException
     */
    public function testThrowInvalidRedirectUrl(): void
    {
        $this->expectException(InvalidRedirectUrlException::class);
        $this->authorizeUser->handle(new AuthorizeUserRequest(
            $this->user->email(),
            'password',
            $this->client->name(),
            'https://invalid-url.com/callback'
        ));
    }

    /**
     * @throws ClientNotFoundException
     * @throws InvalidCredentialsException
     * @throws InvalidRedirectUrlException
     */
    public function testAuthorizationCodeExpiresInMinutes(): void
    {
        $expiresInMinutes = 24;

        Config::set('auth.authorization_code.expires_at_minutes', $expiresInMinutes);

        $dateTimeImmutableBeforeRunService = new DateTimeImmutable();

        $authorizationCode = $this->authorizeUser->handle(new AuthorizeUserRequest(
            $this->user->email(),
            'password',
            $this->client->name(),
            $this->client->redirectUrl()
        ));

        $dateInterval = $authorizationCode->expiresAt()->diff($dateTimeImmutableBeforeRunService);
        $this->assertEquals($expiresInMinutes, $dateInterval->i);
    }
}
