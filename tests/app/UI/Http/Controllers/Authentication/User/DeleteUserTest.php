<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use AuthMicroservice\Authentication\Domain\Model\User\User;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    private User $user;
    private User $userToDelete;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = entity(User::class)->create([
            'admin' => 1,
        ]);
        $this->userToDelete = entity(User::class)->create([
            'admin' => 0,
        ]);
    }

    public function testRouteNotWorkingIfUserNotLogged(): void
    {
        $httpRequest = $this->deleteJson('/user/' . $this->userToDelete->id());

        $this->assertSame(401, $httpRequest->getStatusCode());
    }

    public function testRouteWorksIfUserLogged(): void
    {
        $httpRequest = $this->actingAs($this->user)->deleteJson('/user/' . $this->userToDelete->id());

        $this->assertSame(200, $httpRequest->getStatusCode());
    }

}
