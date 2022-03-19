<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Application\Service\User\CreateUser;
use AuthMicroservice\Authentication\Application\Service\User\CreateUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\UserCreated;
use Exception;
use Tests\TestCase;
use function bcrypt;

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
        $request = new CreateUserRequest('test', 'test@test.test', bcrypt('test'));
        $this->createUser->handle($request);

        $this->assertDatabaseHas('user', [
            'username' => 'test',
            'email' => 'test@test.test',
        ]);
    }

    /**
     * @throws Exception
     */
    public function testFireUserCreated(): void
    {
        $this->expectsEvents(UserCreated::class);

        $request = new CreateUserRequest('test', 'test@test.test', bcrypt('test'));
        $this->createUser->handle($request);
    }


}
