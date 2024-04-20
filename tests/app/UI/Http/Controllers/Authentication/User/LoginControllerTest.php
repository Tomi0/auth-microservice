<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;
use Authentication\Domain\Model\SigningKey\SigningKeyRepository;
use Authentication\Domain\Model\User\UserRepository;
use Illuminate\Support\Facades\Hash;
use Authentication\Domain\Model\User\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    private User $user;
    private AuthorizedHost $authorizedHost;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instanceSigningKeyRepository();
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->user = entity(User::class)->make([
            'password' => Hash::make('secret'),
        ]);
        $this->userRepository->persist($this->user);
        $this->app->instance(UserRepository::class, $this->userRepository);
        $this->app->instance(SigningKeyRepository::class, $this->signingKeyRepository);
        $this->authorizedHost = entity(AuthorizedHost::class)->make([
            'compra.tomibuenalacid.es',
        ]);
    }


    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/auth/login', [
            'email' => null,
            'password' => null,
            'host_name' => $this->authorizedHost->hostName()
        ]);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $request = $this->postJson('/auth/login', [
            'email' => $this->user->email(),
            'password' => 'secret',
            'host_name' => $this->authorizedHost->hostName()
        ]);

        $request->assertStatus(200);
    }

    public function testInvalidCredentials(): void
    {
        $request = $this->postJson('/auth/login', [
            'email' => 'user',
            'password' => 'invalid password',
            'host_name' => $this->authorizedHost->hostName()
        ]);

        $request->assertStatus(401);
    }
}
