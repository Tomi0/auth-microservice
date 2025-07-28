<?php

namespace App\UI\Http\Controllers\Authentication\TokenResetPassword;

use App\UI\Http\Validators\Authentication\TokenResetPassword\GenerateTokenResetPasswordValidator;
use Authentication\Application\Service\TokenResetPassword\GenerateTokenResetPassword;
use Authentication\Application\Service\TokenResetPassword\GenerateTokenResetPasswordRequest;
use Authentication\Domain\Model\User\UserNotFoundException;
use Illuminate\Http\JsonResponse;

class GenerateTokenResetPasswordController
{
    private GenerateTokenResetPassword $generateTokenResetPassword;

    public function __construct(GenerateTokenResetPassword $generateTokenResetPassword)
    {
        $this->generateTokenResetPassword = $generateTokenResetPassword;
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(GenerateTokenResetPasswordValidator $request): JsonResponse
    {
        $this->generateTokenResetPassword->handle(new GenerateTokenResetPasswordRequest($request->input('email')));

        return response()->json();
    }


}
