<?php

namespace Tests\app\UI\Http\Controllers\Authentication\AccessToken;

use Authentication\Application\Service\AccessToken\GetAccessToken;
use Authentication\Application\Service\AccessToken\GetAccessTokenRequest;
use Authentication\Domain\Model\AccessToken\AccessToken;
use Authentication\Domain\Model\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class GetAccessTokenControllerTest extends TestCase
{
    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/oauth/token', [
            'clientName' => null,
            'clientSecret' => null,
            'code' => null,
        ]);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $this->mock(GetAccessToken::class, function (MockInterface $mock) {
            $accessToken = new AccessToken('Bearer', 'token', entity(User::class)->make());
            $mock->shouldReceive('handle')
                ->once()
                ->withArgs(function ($request) {
                    return $request == new GetAccessTokenRequest(
                            'secret',
                            'authorization-code',
                            'client-test',
                        );
                })
                ->andReturn($accessToken);
        });
        $request = $this->postJson('/oauth/token', [
            'clientName' => 'client-test',
            'clientSecret' => 'secret',
            'code' => 'authorization-code',
        ]);

        $request->assertStatus(200);
    }
}
