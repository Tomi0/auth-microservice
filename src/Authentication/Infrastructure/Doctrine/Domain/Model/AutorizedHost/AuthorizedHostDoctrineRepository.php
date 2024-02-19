<?php

namespace Authentication\Infrastructure\Doctrine\Domain\Model\AutorizedHost;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostNotFoundException;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostRepository;
use Doctrine\ORM\EntityRepository;

class AuthorizedHostDoctrineRepository extends EntityRepository implements AuthorizedHostRepository
{

    /**
     * @inheritDoc
     */
    public function ofHostName(string $hostName): AuthorizedHost
    {
        $authorizedHost = $this->findOneBy(['hostName' => $hostName]);

        if ($authorizedHost === null)
            throw new AuthorizedHostNotFoundException('Host ' . $hostName . ' not found');

        return $authorizedHost;
    }
}
