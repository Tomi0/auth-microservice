<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Validators\Authentication\User\AuthorizeUserValidator;
use Authentication\Application\Service\User\AuthorizeUser;
use Authentication\Application\Service\User\AuthorizeUserRequest;
use Authentication\Domain\Model\AuthorizationCode\InvalidRedirectUrlException;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Illuminate\Http\JsonResponse;

class AuthorizeUserController
{
    private AuthorizeUser $authorizeUser;

    public function __construct(AuthorizeUser $authorizeUser)
    {
        $this->authorizeUser = $authorizeUser;
    }

    /**
     * @throws ClientNotFoundException
     * @throws InvalidCredentialsException
     * @throws InvalidRedirectUrlException
     */
    public function __invoke(AuthorizeUserValidator $authorizeUserValidator): JsonResponse
    {
        return response()->json($this->authorizeUser->handle(new AuthorizeUserRequest(
            $authorizeUserValidator->input('email'),
            $authorizeUserValidator->input('password'),
            $authorizeUserValidator->input('clientName'),
            $authorizeUserValidator->input('redirectUrl')
        )));
    }
}
