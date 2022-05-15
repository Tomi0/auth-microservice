<?php

namespace AuthMicroservice\Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Doctrine\ORM\EntityRepository;

class TokenResetPasswordDoctrineRepository extends EntityRepository implements TokenResetPasswordRepository
{

    public function persist(TokenResetPassword $tokenResetPassword): void
    {
        $em = $this->getEntityManager();
        $em->persist($tokenResetPassword);
        $em->flush();
    }
}
