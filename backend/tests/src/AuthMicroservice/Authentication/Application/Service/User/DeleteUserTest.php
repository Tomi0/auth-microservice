<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Application\Service\User\DeleteUser;
use AuthMicroservice\Authentication\Application\Service\User\DeleteUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserDeleted;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    private User $userToDelete;
    private User $adminUser;
    private DeleteUser $deleteUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deleteUser = $this->app->make(DeleteUser::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->userToDelete = entity(User::class)->create([
            'admin' => false,
        ]);
        $this->adminUser = entity(User::class)->create([
            'admin' => true,
        ]);
    }

    public function testThrowUserHasNotPermissions(): void
    {
        $this->expectException(UserHasNotPermissionsException::class);
        $this->deleteUser->handle(new DeleteUserRequest($this->adminUser->id(), $this->userToDelete));
    }

    /**
     * @throws UserHasNotPermissionsException
     */
    public function testUserIsDeleted(): void
    {
        $userToDeleteId = $this->userToDelete->id();
        $this->deleteUser->handle(new DeleteUserRequest($userToDeleteId, $this->adminUser));

        $this->assertDatabaseMissing('user', [
            'id' => $userToDeleteId,
        ]);
    }

    public function testFireUserDeleted(): void
    {
        $this->expectsEvents(UserDeleted::class);
        $this->deleteUser->handle(new DeleteUserRequest($this->userToDelete->id(), $this->adminUser));
    }

    public function testThrowUserNotFoundException(): void
    {
        $this->doesntExpectEvents(UserDeleted::class);
        $this->expectException(UserNotFoundException::class);
        $this->deleteUser->handle(new DeleteUserRequest(Uuid::uuid4(), $this->adminUser));
    }

}
