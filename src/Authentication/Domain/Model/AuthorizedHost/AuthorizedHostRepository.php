<?php

namespace Authentication\Domain\Model\AuthorizedHost;

interface AuthorizedHostRepository
{
    /**
     * @throws AuthorizedHostNotFoundException
     */
    public function ofHostName(string $hostName): AuthorizedHost;
}
