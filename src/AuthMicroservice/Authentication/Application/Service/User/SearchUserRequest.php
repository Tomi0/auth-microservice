<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

class SearchUserRequest
{
    private ?string $email;
    private ?bool $admin;

    public function __construct(?string $email, ?bool $admin)
    {
        $this->email = $email;
        $this->admin = $admin;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function admin(): ?bool
    {
        return $this->admin;
    }
}
