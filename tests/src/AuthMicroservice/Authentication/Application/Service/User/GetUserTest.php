<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Authentication\Domain\Model\User\UserRepository;
use Ramsey\Uuid\Uuid;
use Authentication\Application\Service\User\GetUser;
use Authentication\Application\Service\User\GetUserRequest;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserHasNotPermissionsException;
use Authentication\Domain\Model\User\UserNotFoundException;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    private GetUser $getUser;
    private User $user;
    private User $anotherUser;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->getUser = new GetUser($this->userRepository);
        $this->initDatosTest();
    }


    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->make();
        $this->anotherUser = entity(User::class)->make();
        $this->userRepository->persist($this->user);
        $this->userRepository->persist($this->anotherUser);
    }

    public function testReturnUserInstance(): void
    {
        $result = $this->getUser->handle(new GetUserRequest(
            $this->user->id(),
            $this->user->id(),
        ));

        $this->assertInstanceOf(User::class, $result);
    }

    public function testReturnExpectedUser(): void
    {
        $result = $this->getUser->handle(new GetUserRequest(
            $this->user->id(),
            $this->user->id(),
        ));

        $this->assertEquals($this->user, $result);
    }

    public function testUserHasNotPermissionsExceptionWhenTryingToGetAnotherUser(): void
    {
        $this->expectException(UserHasNotPermissionsException::class);

        $this->getUser->handle(new GetUserRequest(
            $this->user->id(),
            $this->anotherUser->id(),
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
