<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use App\UI\Http\Validators\Authentication\User\SearchUserValidator;
use AuthMicroservice\Authentication\Application\Service\User\SearchUser;
use AuthMicroservice\Authentication\Application\Service\User\SearchUserRequest;
use Illuminate\Http\JsonResponse;

class SearchUserController extends Controller
{

    private SearchUser $searchUser;

    public function __construct(SearchUser $searchUser)
    {
        $this->searchUser = $searchUser;
    }

    public function __invoke(SearchUserValidator $searchUserValidator): JsonResponse
    {
        return response()->json($this->searchUser->handle(new SearchUserRequest(
            $searchUserValidator->input('email'),
            $searchUserValidator->input('admin')
        )));
    }
}
