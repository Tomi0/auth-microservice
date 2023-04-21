<?php

namespace Tests\app\UI\Http\Controllers\Authentication\User;

use Tests\TestCase;

class CreateUserControllerTest extends TestCase
{
    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/auth/register', [
            'email' => null,
            'password' => null
        ]);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $request = $this->postJson('/auth/register', [
            'email' => 'test@test.test',
            'password' => 'secret'
        ]);

        $request->assertStatus(201);
    }
}
