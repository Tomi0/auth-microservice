<?php

namespace AuthMicroservice\Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Doctrine\ORM\EntityRepository;

class TokenResetPasswordDoctrineRepository extends EntityRepository implements TokenResetPasswordRepository
{

    public function persist(TokenResetPassword $tokenResetPassword): void
    {
        $em = $this->getEntityManager();
        $em->persist($tokenResetPassword);
        $em->flush();
    }

    /**
     * @inheritDoc
     */
    public function ofToken(string $tokenResetPassword): TokenResetPassword
    {
        $tokenResetPassword = $this->findOneBy(['token' => $tokenResetPassword]);

        if ($tokenResetPassword === null)
            throw new TokenResetPasswordNotFoundException();

        return $tokenResetPassword;
    }

    /**
     * @inheritDoc
     */
    public function ofEmail(string $email): TokenResetPassword
    {
        $tokenResetPassword = $this->findOneBy(['email' => $email]);

        if ($tokenResetPassword === null)
            throw new TokenResetPasswordNotFoundException();

        return $tokenResetPassword;
    }
}
