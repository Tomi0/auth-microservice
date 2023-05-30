<?php

namespace Tests\src\AuthMicroservice\Authentication\Domain\Service\User;

use Illuminate\Support\Facades\Hash;
use Authentication\Domain\Service\User\CheckPasswordHash;
use Tests\TestCase;

class CheckPasswordHashTest extends TestCase
{
    private CheckPasswordHash $checkPasswordHash;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checkPasswordHash = $this->app->make(CheckPasswordHash::class);
    }

    public function testPasswordMatch(): void
    {
        $this->assertTrue($this->checkPasswordHash->execute('secret', Hash::make('secret')));
    }

    public function testPasswordDoesNotMatch(): void
    {
        $this->assertFalse($this->checkPasswordHash->execute('not secret', Hash::make('secret')));
    }
}
