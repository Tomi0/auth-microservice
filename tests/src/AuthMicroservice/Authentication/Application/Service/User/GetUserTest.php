<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Application\Service\User\GetUser;
use AuthMicroservice\Authentication\Application\Service\User\GetUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    private GetUser $getUser;
    private User $user;
    private User $anotherUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getUser = $this->app->make(GetUser::class);
        $this->initDatosTest();
    }


    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->create();
        $this->anotherUser = entity(User::class)->create();
    }

    public function testReturnUserInstance(): void
    {
        $result = $this->getUser->handle(new GetUserRequest(
            $this->user->id()->toString(),
            $this->user->id()->toString(),
        ));

        $this->assertInstanceOf(User::class, $result);
    }

    public function testReturnExpectedUser(): void
    {
        $result = $this->getUser->handle(new GetUserRequest(
            $this->user->id()->toString(),
            $this->user->id()->toString(),
        ));

        $this->assertEquals($this->user, $result);
    }

    public function testUserHasNotPermissionsExceptionWhenTryingToGetAnotherUser(): void
    {
        $this->expectException(UserHasNotPermissionsException::class);

        $this->getUser->handle(new GetUserRequest(
            $this->user->id()->toString(),
            $this->anotherUser->id()->toString(),
        ));
    }

    public function testThrowUserNotFoundWhenUserDoesNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $fakeUserId = Uuid::uuid4();

        $this->getUser->handle(new GetUserRequest(
            $fakeUserId,
            $fakeUserId,
        ));
    }


}
