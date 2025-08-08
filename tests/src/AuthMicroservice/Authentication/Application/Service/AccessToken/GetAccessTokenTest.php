<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\AccessToken;

use Authentication\Application\Service\AccessToken\GetAccessToken;
use Authentication\Application\Service\AccessToken\GetAccessTokenRequest;
use Authentication\Domain\Model\AccessToken\AccessToken;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;
use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Authentication\Domain\Model\AuthorizationCode\InvalidAuthorizationCodeException;
use Authentication\Domain\Model\Client\Client;
use Authentication\Domain\Model\Client\ClientNameAndSecretMissmatchException;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\Client\ClientRepository;
use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Authentication\Infrastructure\Laravel\Domain\Model\AuthorizationCode\AuthorizationCodeInMemoryRepository;
use Authentication\Infrastructure\Laravel\Domain\Model\Client\ClientInMemoryRepository;
use Mockery\MockInterface;
use Tests\TestCase;

class GetAccessTokenTest extends TestCase
{
    /**
     * @var ClientInMemoryRepository
     */
    private ClientRepository $clientRepository;
    /**
     * @var AuthorizationCodeInMemoryRepository
     */
    private AuthorizationCodeRepository $authorizationCodeRepository;

    private GetAccessToken $getAccessToken;

    private Client $client;
    private AuthorizationCode $authorizationCode;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientRepository = $this->app->make(ClientRepository::class);
        $this->authorizationCodeRepository = $this->app->make(AuthorizationCodeRepository::class);
        $this->userRepository = $this->app->make(UserRepository::class);

        /** @var User $user */
        $user = entity(User::class)->make();
        $this->client = entity(Client::class)->make();
        $this->authorizationCode = entity(AuthorizationCode::class)->make([
            'clientId' => $this->client->id(),
            'userId' => $user->id(),
        ]);

        $this->clientRepository->persist($this->client);
        $this->authorizationCodeRepository->persist($this->authorizationCode);
        $this->userRepository->persist($user);

        $generateJwt = $this->mock(GenerateJwtToken::class, function (MockInterface $mock) {
            $mock->shouldReceive('execute')->andReturn('jwt-token');
        });
        $signingKeyRepository = $this->mock(SigningKeyRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('first')->andReturn(entity(SigningKey::class)->make());
        });

        $this->getAccessToken = $this->app->makeWith(GetAccessToken::class, [
            'clientRepository' => $this->clientRepository,
            'authorizationCodeRepository' => $this->authorizationCodeRepository,
            'userRepository' => $this->userRepository,
            'signingKeyRepository' => $signingKeyRepository,
            'jwtToken' => $generateJwt,
        ]);
    }


    public function testReturnAccessToken(): void
    {
        $getAccessTokenRequest = new GetAccessTokenRequest(
            $this->client->clientSecret(),
            $this->authorizationCode->code(),
            $this->client->name()
        );

        $accessToken = $this->getAccessToken->handle($getAccessTokenRequest);

        $this->assertInstanceOf(AccessToken::class, $accessToken);
    }

    public function testThrowClientNameAndSecretMissmatchExceptionWhenSecretIsWrong(): void
    {
        $getAccessTokenRequest = new GetAccessTokenRequest(
            'wrong-secret',
            $this->authorizationCode->code(),
            $this->client->name()
        );

        $this->expectException(ClientNameAndSecretMissmatchException::class);
        $this->getAccessToken->handle($getAccessTokenRequest);
    }

    public function testThrowClientNotFoundExceptionWhenClientNameIsWrong(): void
    {
        $getAccessTokenRequest = new GetAccessTokenRequest(
            $this->client->clientSecret(),
            $this->authorizationCode->code(),
            'wrong-client-name'
        );

        $this->expectException(ClientNotFoundException::class);
        $this->getAccessToken->handle($getAccessTokenRequest);
    }

    public function testThrowInvalidAuthorizationCodeExceptionWhenWrongCode(): void
    {
        $getAccessTokenRequest = new GetAccessTokenRequest(
            $this->client->clientSecret(),
            'wrong-authorization-code',
            $this->client->name()
        );

        $this->expectException(InvalidAuthorizationCodeException::class);
        $this->getAccessToken->handle($getAccessTokenRequest);
    }

    public function testReturnAccessTokenIsTheExpectedOne(): void
    {
        $getAccessTokenRequest = new GetAccessTokenRequest(
            $this->client->clientSecret(),
            $this->authorizationCode->code(),
            $this->client->name()
        );

        $accessToken = $this->getAccessToken->handle($getAccessTokenRequest);

        $this->assertInstanceOf(User::class, $accessToken->user());
        $this->assertEquals($this->authorizationCode->userId(), $accessToken->user()->id());
        $this->assertEquals('Bearer', $accessToken->type());
        $this->assertEquals('jwt-token', $accessToken->token());
    }
}
