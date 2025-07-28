<?php

namespace Tests\src\AuthMicroservice\Authentication\Domain\Service\User;

use Authentication\Domain\Model\SigningKey\SigningKey;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Service\User\GenerateJwtToken;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GenerateJwtTokenTest extends TestCase
{
    private GenerateJwtToken $generateJwtToken;
    private User $user;
    private SigningKey $signingKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateJwtToken = $this->app->make(GenerateJwtToken::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->user = entity(User::class)->make();
        $this->signingKey = new SigningKey(Uuid::uuid4());
    }

    public function testGeneratedJwtTokenIsValid(): void
    {
        $resultToken = $this->generateJwtToken->execute($this->user, $this->signingKey);

        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($resultToken);

        $validator = new Validator();

        $this->assertTrue(
            $validator->validate(
                $token,
                new SignedWith(
                    new Sha256(),
                    InMemory::plainText($this->signingKey->publicKey()
                    )
                )
            )
        );
    }

}
