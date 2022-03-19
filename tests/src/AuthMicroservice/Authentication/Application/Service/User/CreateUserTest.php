<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Application\Service\User\CreateUser;
use AuthMicroservice\Authentication\Application\Service\User\CreateUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserCreated;
use Exception;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    private CreateUser $createUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser = $this->app->make(CreateUser::class);
        $this->withoutEvents();
    }

    public function testUserExistsInDataBase(): void
    {
        $request = new CreateUserRequest('test@test.test', 'test');
        $this->createUser->handle($request);

        $this->assertDatabaseHas('user', [
            'email' => 'test@test.test',
        ]);
    }

    /**
     * @throws Exception
     */
    public function testFireUserCreated(): void
    {
        $this->expectsEvents(UserCreated::class);

        $request = new CreateUserRequest('test@test.test', 'test');
        $this->createUser->handle($request);
    }

    public function testReturnValueIsAnUser(): void
    {
        $request = new CreateUserRequest('test@test.test', 'test');
        $result = $this->createUser->handle($request);

        $this->assertInstanceOf(User::class, $result);
    }

    public function testReturnExpectedUser(): void
    {
        $request = new CreateUserRequest('test@test.test', 'test');
        $result = $this->createUser->handle($request);

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame('test@test.test', $result->email());
    }


}
