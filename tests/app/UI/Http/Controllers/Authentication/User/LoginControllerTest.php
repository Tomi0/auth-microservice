<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use Illuminate\Support\Facades\Hash;
use Authentication\Domain\Model\User\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = entity(User::class)->create([
            'password' => Hash::make('secret'),
        ]);
    }


    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/auth/login', [
            'email' => null,
            'password' => null
        ]);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $request = $this->postJson('/auth/login', [
            'email' => $this->user->email(),
            'password' => 'secret'
        ]);

        $request->assertStatus(200);
    }

    public function testInvalidCredentials(): void
    {
        $request = $this->postJson('/auth/login', [
            'email' => 'user',
            'password' => 'invalid password'
        ]);

        $request->assertStatus(401);
    }
}
