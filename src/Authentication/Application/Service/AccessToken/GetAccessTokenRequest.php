<?php

namespace Authentication\Application\Service\AccessToken;

readonly class GetAccessTokenRequest
{
    public string $clientSecret;
    public string $code;
    public string $clientName;

    public function __construct(string $clientSecret, string $code, string $clientName)
    {
        $this->clientSecret = $clientSecret;
        $this->code = $code;
        $this->clientName = $clientName;
    }
}
