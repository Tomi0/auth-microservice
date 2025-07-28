<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\AuthorizationCode;

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCodeRepository;
use Doctrine\ORM\EntityRepository;

class AuthorizationCodeDoctrineRepository extends EntityRepository implements AuthorizationCodeRepository
{

}
