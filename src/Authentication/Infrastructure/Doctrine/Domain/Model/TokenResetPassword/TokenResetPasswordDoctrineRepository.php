<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\TokenResetPassword;

use Doctrine\ORM\EntityRepository;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;

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
