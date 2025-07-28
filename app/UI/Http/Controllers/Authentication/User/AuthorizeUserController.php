<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Validators\Authentication\User\LoginValidator;
use Authentication\Application\Service\User\AuthorizeUser;
use Authentication\Application\Service\User\LoginUserRequest;
use Authentication\Domain\Model\Client\ClientNotFoundException;
use Authentication\Domain\Model\SigningKey\SigningKeyNotFoundException;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Illuminate\Http\JsonResponse;

class AuthorizeUserController
{
    private AuthorizeUser $loginUser;

    public function __construct(AuthorizeUser $loginUser)
    {
        $this->loginUser = $loginUser;
    }

    /**
     * @throws InvalidCredentialsException
     * @throws ClientNotFoundException
     * @throws SigningKeyNotFoundException
     */
    public function __invoke(LoginValidator $loginValidator): JsonResponse
    {
        return response()->json($this->loginUser->handle(new LoginUserRequest(
            $loginValidator->input('email'),
            $loginValidator->input('password'),
            $loginValidator->input('host_name')
        )));
    }
}
