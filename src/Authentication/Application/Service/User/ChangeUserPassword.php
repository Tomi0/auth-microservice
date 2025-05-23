<?php

namespace Authentication\Application\Service\User;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\UserHasNotPermissionsException;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserPasswordChanged;
use Authentication\Domain\Model\User\UserRepository;
use Authentication\Domain\Service\User\EncodePassword;

class ChangeUserPassword
{

    private UserRepository $userRepository;
    private TokenResetPasswordRepository $tokenResetPasswordRepository;
    private EncodePassword $encodePassword;

    public function __construct(UserRepository               $userRepository,
                                TokenResetPasswordRepository $tokenResetPasswordRepository,
                                EncodePassword               $encodePassword)
    {
        $this->userRepository = $userRepository;
        $this->tokenResetPasswordRepository = $tokenResetPasswordRepository;
        $this->encodePassword = $encodePassword;
    }

    /**
     * @throws TokenResetPasswordNotFoundException
     * @throws UserHasNotPermissionsException
     * @throws UserNotFoundException
     */
    public function handle(ChangeUserPasswordRequest $changeUserPasswordRequest): void
    {
        $tokenResetPassword = $this->tokenResetPasswordRepository->ofToken($changeUserPasswordRequest->tokenResetPassword);

        if ($tokenResetPassword->email() !== $changeUserPasswordRequest->userEmail)
            throw new UserHasNotPermissionsException();

        $user = $this->userRepository->ofEmail($changeUserPasswordRequest->userEmail);

        $user->changePassword($this->encodePassword->execute($changeUserPasswordRequest->password));

        $this->userRepository->persist($user);
    }
}
