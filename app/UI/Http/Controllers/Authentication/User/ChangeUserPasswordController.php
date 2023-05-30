<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use App\UI\Http\Validators\Authentication\User\ChangeUserPasswordValidator;
use Illuminate\Http\JsonResponse;
use Authentication\Application\Service\User\ChangeUserPassword;
use Authentication\Application\Service\User\ChangeUserPasswordRequest;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use Authentication\Domain\Model\User\UserHasNotPermissionsException;
use Authentication\Domain\Model\User\UserNotFoundException;

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
