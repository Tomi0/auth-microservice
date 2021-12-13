<?php

namespace Tests\http\Controllers\Authentication\User;

use Tests\TestCase;

class CreateUserControllerTest extends TestCase
{
    public function testRouteValidatorWorks(): void
    {
        $request = $this->postJson('/auth/register', [
            'username' => null,
            'email' => null,
            'password' => null
        ]);

        $request->assertStatus(422);
    }

    public function testRouteWorks(): void
    {
        $request = $this->postJson('/auth/register', [
            'username' => 'manolo',
            'email' => 'test@test.test',
            'password' => 'secret'
        ]);

        $request->assertStatus(200);
    }
}
