<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\AuthorizedHost;

use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostNotFoundException;
use Authentication\Domain\Model\AuthorizedHost\AuthorizedHostRepository;

class AuthorizedHostInMemoryRepository implements AuthorizedHostRepository
{
    private array $authorizedHosts = [];

    public function ofHostName(string $hostName): AuthorizedHost
    {
        foreach ($this->authorizedHosts as $authorizedHost) {
            if ($authorizedHost->hostName() === $hostName) {
                return $authorizedHost;
            }
        }
        throw new AuthorizedHostNotFoundException();
    }

    public function persist(AuthorizedHost $authorizedHost): void
    {
        $this->authorizedHosts[] = $authorizedHost;
    }
}
