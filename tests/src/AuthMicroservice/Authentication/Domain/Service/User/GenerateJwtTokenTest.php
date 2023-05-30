<?php

namespace Tests\src\AuthMicroservice\Authentication\Domain\Service\User;

use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Tests\TestCase;

class GenerateJwtTokenTest extends TestCase
{
    private GenerateJwtToken $generateJwtToken;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateJwtToken = $this->app->make(GenerateJwtToken::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->create();
    }

    public function testGeneratedJwtTokenIsValid(): void
    {
        $resultToken = $this->generateJwtToken->execute($this->user);

        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($resultToken);

        $validator = new Validator();

        $this->assertTrue($validator->validate($token, new SignedWith(new Sha256(), InMemory::file(config('filesystems.disks.jwt_signing_keys.root') . '/' . config('jwt.jwt_public_key_filename')))));
    }

}
