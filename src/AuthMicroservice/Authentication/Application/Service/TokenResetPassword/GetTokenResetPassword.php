<?php

namespace AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRequested;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;
use AuthMicroservice\Shared\Domain\Service\RandomStringGenerator;

class GetTokenResetPassword
{
    private TokenResetPasswordRepository $tokenResetPasswordRepository;
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;
    private RandomStringGenerator $randomStringGenerator;

    public function __construct(TokenResetPasswordRepository $tokenResetPasswordRepository,
                                UserRepository               $userRepository,
                                EventDispatcher              $eventDispatcher,
                                RandomStringGenerator        $randomStringGenerator)
    {
        $this->tokenResetPasswordRepository = $tokenResetPasswordRepository;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->randomStringGenerator = $randomStringGenerator;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserHasNotPermissionsException
     */
    public function handle(GetTokenResetPasswordRequest $getTokenResetPasswordRequest): TokenResetPassword
    {
        $user = $this->userRepository->ofEmail($getTokenResetPasswordRequest->email());

        if ($getTokenResetPasswordRequest->userLogged()->id()->equals($user->id()) === false && $getTokenResetPasswordRequest->userLogged()->admin() === false)
            throw new UserHasNotPermissionsException();

        $tokenResetPassword = new TokenResetPassword($user->email(), $this->randomStringGenerator->execute());

        $this->tokenResetPasswordRepository->persist($tokenResetPassword);

        $this->eventDispatcher->execute(new TokenResetPasswordRequested($user, $getTokenResetPasswordRequest->userLogged(), $tokenResetPassword));

        return $tokenResetPassword;
    }
}
