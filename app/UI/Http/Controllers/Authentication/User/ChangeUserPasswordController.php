<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use App\UI\Http\Validators\Authentication\User\ChangeUserPasswordValidator;
use AuthMicroservice\Authentication\Application\Service\User\ChangeUserPassword;
use AuthMicroservice\Authentication\Application\Service\User\ChangeUserPasswordRequest;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use Illuminate\Http\JsonResponse;

class ChangeUserPasswordController extends Controller
{
    private ChangeUserPassword $changeUserPassword;

    public function __construct(ChangeUserPassword $changeUserPassword)
    {
        $this->changeUserPassword = $changeUserPassword;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserHasNotPermissionsException
     * @throws TokenResetPasswordNotFoundException
     */
    public function __invoke(ChangeUserPasswordValidator $changeUserPasswordValidator): JsonResponse
    {
        $this->changeUserPassword->handle(new ChangeUserPasswordRequest(
            $changeUserPasswordValidator->input('token'),
            $changeUserPasswordValidator->input('password'),
            $changeUserPasswordValidator->input('email')
        ));

        return response()->json();
    }
}
