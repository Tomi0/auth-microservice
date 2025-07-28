<?php

namespace Authentication\Application\Service\User;

readonly class AuthorizeUserRequest
{
    public string $email;
    public string $password;
    public string $clientName;
    public string $redirectUrl;

    public function __construct(string $email, string $password, string $clientName, string $redirectUrl)
    {
        $this->email = $email;
        $this->password = $password;
        $this->clientName = $clientName;
        $this->redirectUrl = $redirectUrl;
    }
}
