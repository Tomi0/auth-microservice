<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Authentication\Domain\Model\User\InvalidCredentialsException;
use Authentication\Domain\Model\User\UserHasNotPermissionsException;
use Authentication\Domain\Model\User\UserNotLoggedException;
use Shared\Domain\Model\DomainValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|Response
    {
        if ($e instanceof InvalidCredentialsException) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        if ($e instanceof UserHasNotPermissionsException) {
            return response()->json(['message' => 'User has not permissions'], 403);
        }
        if ($e instanceof UserNotLoggedException) {
            return response()->json(['message' => 'User not logged'], 401);
        }
        if ($e instanceof DomainValidationException) {
            return response()->json(['message' => 'User not logged', 'errors' => [$e->fieldName() => [$e->getMessage()]]], 422);
        }

        return parent::render($request, $e);
    }


}
