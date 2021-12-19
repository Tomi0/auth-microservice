<?php

namespace Tests\src\Authentication\Domain\Service\User;

use Authentication\Domain\Service\User\CheckEmailAndPasswordMatch;
use Authentication\Infrastructure\Domain\Model\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CheckEmailAndPasswordMatchTest extends TestCase
{
    private Model $user;
    private CheckEmailAndPasswordMatch $checkEmailAndPasswordMatch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checkEmailAndPasswordMatch = $this->app->make(CheckEmailAndPasswordMatch::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = User::factory()->create([
            'password' => Hash::make('secret')
        ]);
    }

    public function testReturnTrueWhenValid(): void
    {
        $result = $this->checkEmailAndPasswordMatch->execute($this->user['email'], 'secret');
        $this->assertTrue($result);
    }

    public function testReturnFalseWhenInvalidEmail(): void
    {
        $result = $this->checkEmailAndPasswordMatch->execute('manolo', 'secret');
        $this->assertFalse($result);
    }

    public function testReturnFalseWhenInvalidPassword(): void
    {
        $result = $this->checkEmailAndPasswordMatch->execute($this->user['email'], 'invalid password');
        $this->assertFalse($result);
    }
}
