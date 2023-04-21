<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use App\UI\Http\Validators\Authentication\User\CreateUserValidator;
use AuthMicroservice\Authentication\Application\Service\User\CreateUser;
use AuthMicroservice\Authentication\Application\Service\User\CreateUserRequest;
use Illuminate\Http\JsonResponse;

class CreateUserController extends Controller
{
    private CreateUser $createUser;

    public function __construct(CreateUser $createUser)
    {
        $this->createUser = $createUser;
    }

    public function __invoke(CreateUserValidator $createUserValidator): JsonResponse
    {
        return response()->json($this->createUser->handle(new CreateUserRequest(
            $createUserValidator->input('email'),
            $createUserValidator->input('password')
        )), 201);
    }
}
