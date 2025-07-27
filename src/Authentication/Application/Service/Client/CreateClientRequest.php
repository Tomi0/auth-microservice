<?php

namespace Authentication\Application\Service\Client;

readonly class CreateClientRequest
{
    public string $name;
    public string $redirectUrl;

    public function __construct(string $name, string $redirectUrl)
    {
        $this->name = $name;
        $this->redirectUrl = $redirectUrl;
    }
}
