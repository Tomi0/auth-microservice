<?php

namespace Authentication\Domain\Model\AuthorizedHost;

use DateTime;
use Ramsey\Uuid\UuidInterface;

class AuthorizedHost
{
    private UuidInterface $id;
    private string $hostName;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    /**
     * @param UuidInterface $id
     * @param string $hostName
     * @param DateTime $createdAt
     * @param DateTime $updatedAt
     */
    public function __construct(UuidInterface $id, string $hostName)
    {
        $this->id = $id;
        $this->hostName = $hostName;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function hostName(): string
    {
        return $this->hostName;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
