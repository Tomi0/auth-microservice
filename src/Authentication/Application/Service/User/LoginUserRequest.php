<?php

namespace Authentication\Application\Service\User;

readonly class LoginUserRequest
{
    public string $email;
    public string $password;
    public ?string $hostName;

    public function __construct(string $email, string $password, ?string $hostName)
    {
        $this->email = $email;
        $this->password = $password;
        $this->hostName = $hostName;
    }
}
