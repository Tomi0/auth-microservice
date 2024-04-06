<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use Authentication\Domain\Model\User\EmailAlreadyInUseException;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\EncodePassword;
use Exception;
use Authentication\Application\Service\User\CreateUser;
use Authentication\Application\Service\User\CreateUserRequest;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserCreated;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    private CreateUser $createUser;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->createUser = new CreateUser(
            $this->userRepository,
            $this->app->make(EncodePassword::class)
        );
        $this->withoutEvents();
    }

    public function testUserExistsInDataBase(): void
    {
        $email = 'test@test.test';
        $request = new CreateUserRequest('test', $email, 'test');
        $this->createUser->handle($request);

        $this->assertInstanceOf(User::class, $this->userRepository->ofEmail($email));
    }

    public function testThrowEmailAlreadyInUse(): void
    {
        $this->expectExceptionMessage('Email already in use');
        $this->expectException(EmailAlreadyInUseException::class);

        $this->userRepository->persist(entity(User::class)->make(['email' => 'test@test.test']));
        $request = new CreateUserRequest('test', 'test@test.test', 'test');

        $this->createUser->handle($request);
    }

    /**
     * @throws Exception
     */
    public function testFireUserCreated(): void
    {
        $this->assertEventPublished(UserCreated::class);

        $request = new CreateUserRequest('test', 'test@test.test', 'test');
        $this->createUser->handle($request);
    }

    public function testReturnValueIsAnUser(): void
    {
        $request = new CreateUserRequest('test', 'test@test.test', 'test');
        $result = $this->createUser->handle($request);

        $this->assertInstanceOf(User::class, $result);
    }

    public function testReturnExpectedUser(): void
    {
        $request = new CreateUserRequest('test', 'test@test.test', 'test');
        $result = $this->createUser->handle($request);

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame('test@test.test', $result->email());
    }


}
