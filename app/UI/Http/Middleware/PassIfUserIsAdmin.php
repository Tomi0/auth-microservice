<?php

namespace App\UI\Http\Middleware;

use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotLoggedException;
use Closure;
use Illuminate\Http\Request;

class PassIfUserIsAdmin
{
    /**
     * @throws UserNotLoggedException
     * @throws UserHasNotPermissionsException
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $user = auth()->user();

        if ($user === null)
            throw new UserNotLoggedException();

        if ($user->admin())
            return $next($request);

        throw new UserHasNotPermissionsException();
    }

}
