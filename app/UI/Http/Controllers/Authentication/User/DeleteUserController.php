<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use AuthMicroservice\Authentication\Application\Service\User\DeleteUser;
use AuthMicroservice\Authentication\Application\Service\User\DeleteUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class DeleteUserController extends Controller
{
    private DeleteUser $deleteUser;

    public function __construct(DeleteUser $deleteUser)
    {
        $this->deleteUser = $deleteUser;
    }

    /**
     * @throws \Authentication\Domain\Model\User\UserHasNotPermissionsException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->deleteUser->handle(new DeleteUserRequest(
            Uuid::fromString($request->route('user_id')),
            auth()->user()
        ));

        return response()->json(null, 200);
    }
}
