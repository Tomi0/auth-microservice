<?php

namespace App\UI\Http\Controllers\Authentication\User;

use App\UI\Http\Controllers\Controller;
use AuthMicroservice\Authentication\Application\Service\User\DeleteUser;

class DeleteUserController extends Controller
{
    private DeleteUser $deleteUser;

    public function __construct(DeleteUser $deleteUser)
    {
        $this->deleteUser = $deleteUser;
    }
}
