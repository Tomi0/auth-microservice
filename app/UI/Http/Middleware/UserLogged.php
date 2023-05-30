<?php

namespace App\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Authentication\Domain\Model\User\UserNotLoggedException;

class UserLogged
{
    /**
     * @throws UserNotLoggedException
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check())
            return $next($request);

        throw new UserNotLoggedException();
    }
}
