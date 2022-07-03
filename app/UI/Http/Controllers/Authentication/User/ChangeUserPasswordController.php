<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use App\UI\Http\Validators\Authentication\User\ChangeUserPasswordValidator;
use AuthMicroservice\Authentication\Application\Service\User\ChangeUserPassword;
use AuthMicroservice\Authentication\Application\Service\User\ChangeUserPasswordRequest;
use Illuminate\Http\JsonResponse;

class ChangeUserPasswordController extends Controller
{
    private ChangeUserPassword $changeUserPassword;

    public function __construct(ChangeUserPassword $changeUserPassword)
    {
        $this->changeUserPassword = $changeUserPassword;
    }

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
