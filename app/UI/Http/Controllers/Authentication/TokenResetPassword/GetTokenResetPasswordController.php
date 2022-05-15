<?php

namespace App\UI\Http\Controllers\Authentication\TokenResetPassword;

use App\UI\Http\Validators\Authentication\TokenResetPassword\GetTokenResetPasswordValidator;
use AuthMicroservice\Authentication\Application\Service\TokenResetPassword\GetTokenResetPassword;
use AuthMicroservice\Authentication\Application\Service\TokenResetPassword\GetTokenResetPasswordRequest;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Illuminate\Http\JsonResponse;

class GetTokenResetPasswordController
{
    private GetTokenResetPassword $getTokenResetPassword;

    public function __construct(GetTokenResetPassword $getTokenResetPassword)
    {
        $this->getTokenResetPassword = $getTokenResetPassword;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserHasNotPermissionsException
     */
    public function __invoke(GetTokenResetPasswordValidator $request): JsonResponse
    {
        $tokenResetPassword = $this->getTokenResetPassword->handle(new GetTokenResetPasswordRequest($request->input('email'), auth()->user()));

        return response()->json($tokenResetPassword->token());
    }


}
