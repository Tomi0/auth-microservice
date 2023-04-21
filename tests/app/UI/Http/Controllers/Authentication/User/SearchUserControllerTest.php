<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use AuthMicroservice\Authentication\Application\Service\User\SearchUser;
use AuthMicroservice\Authentication\Application\Service\User\SearchUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class SearchUserControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = entity(User::class)->create([
            'admin' => 1,
        ]);
    }

    public function testRouteNotWorkingIfUserNotLogged(): void
    {
        $this->mock(SearchUser::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->never();
        });
        $httpRequest = $this->getJson('/backoffice-admin/user/search');

        $this->assertSame(401, $httpRequest->getStatusCode());
    }

    public function testRouteWorksIfUserLogged(): void
    {
        $this->mock(SearchUser::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->once()->andReturn([])->withArgs(function ($arg) {
                $expected = new SearchUserRequest('email@test', true);
                return $expected == $arg;
            });
        });
        $httpRequest = $this->getJson('/backoffice-admin/user/search?email=email@test&admin=1', [
            'Authorization' => $this->getJwtToken($this->user),
        ]);

        $this->assertSame(200, $httpRequest->getStatusCode());
    }

    public function testValidatorWorks(): void
    {
        $this->mock(SearchUser::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->never();
        });
        $httpRequest = $this->getJson('/backoffice-admin/user/search?admin=notValid', [
            'Authorization' => $this->getJwtToken($this->user),
        ]);

        $this->assertSame(422, $httpRequest->getStatusCode());
    }

    public function testRouteWorksIfUserLoggedIsNotAdmin(): void
    {
        $this->mock(SearchUser::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->never();
        });

        $notAnAdmin = entity(User::class)->create([
            'admin' => 0,
        ]);

        $httpRequest = $this->getJson('/backoffice-admin/user/search', [
            'Authorization' => $this->getJwtToken($notAnAdmin),
        ]);

        $this->assertSame(403, $httpRequest->getStatusCode());
    }
}
