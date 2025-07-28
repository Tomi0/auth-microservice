<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Validators\Authentication\User\LoginValidator;
use Authentication\Application\Service\User\LoginUser;
use Authentication\Application\Service\User\LoginUserRequest;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Illuminate\Http\JsonResponse;

class LoginController
{
    private LoginUser $loginUser;

    public function __construct(LoginUser $loginUser)
    {
        $this->loginUser = $loginUser;
    }

    /**
     * @throws InvalidCredentialsException
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
