<?php

namespace Tests\src\AuthMicroservice\Authentication\Domain\Service\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Authentication\Domain\Service\User\EncodePassword;
use Tests\TestCase;

class EncodePasswordTest extends TestCase
{
    private EncodePassword $encodePassword;

    protected function setUp(): void
    {
        parent::setUp();
        $this->encodePassword = $this->app->make(EncodePassword::class);
    }

    public function testReturnValueIsHash(): void
    {
        $password = Str::random();

        $resultHash = $this->encodePassword->execute($password);

        $this->assertTrue(Hash::check($password, $resultHash));
    }


}
