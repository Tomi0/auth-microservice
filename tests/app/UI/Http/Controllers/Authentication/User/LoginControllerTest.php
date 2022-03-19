<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use AuthMicroservice\Authentication\Infrastructure\Domain\Model\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    private Model $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
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
            'email' => $this->user['email'],
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
