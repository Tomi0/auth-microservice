<?php

namespace Authentication\Application\Service\User;

readonly class CreateUserRequest
{
    public string $fullName;
    public string $email;
    public string $password;

    public function __construct(string $fullName, string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
        $this->fullName = $fullName;
    }
}
