<?php

namespace AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use AuthMicroservice\Authentication\Domain\Model\User\UserHasNotPermissionsException;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserPasswordChanged;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Authentication\Domain\Service\User\EncodePassword;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;

class ChangeUserPassword
{

    private UserRepository $userRepository;
    private TokenResetPasswordRepository $tokenResetPasswordRepository;
    private EventDispatcher $eventDispatcher;
    private EncodePassword $encodePassword;

    public function __construct(UserRepository               $userRepository,
                                TokenResetPasswordRepository $tokenResetPasswordRepository,
                                EventDispatcher              $eventDispatcher,
                                EncodePassword               $encodePassword)
    {
        $this->userRepository = $userRepository;
        $this->tokenResetPasswordRepository = $tokenResetPasswordRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->encodePassword = $encodePassword;
    }

    /**
     * @throws TokenResetPasswordNotFoundException
     * @throws UserHasNotPermissionsException
     * @throws UserNotFoundException
     */
    public function handle(ChangeUserPasswordRequest $changeUserPasswordRequest): void
    {
        $tokenResetPassword = $this->tokenResetPasswordRepository->ofToken($changeUserPasswordRequest->tokenResetPassword());

        if ($tokenResetPassword->email() !== $changeUserPasswordRequest->userEmail())
            throw new UserHasNotPermissionsException();

        $user = $this->userRepository->ofEmail($changeUserPasswordRequest->userEmail());

        $user->changePassword($this->encodePassword->execute($changeUserPasswordRequest->password()));

        $this->userRepository->persistir($user);

        $this->eventDispatcher->execute(new UserPasswordChanged($user, $tokenResetPassword));
    }
}
