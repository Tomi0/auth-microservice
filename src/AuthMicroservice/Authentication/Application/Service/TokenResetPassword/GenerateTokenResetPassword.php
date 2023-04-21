<?php

namespace AuthMicroservice\Authentication\Application\Service\TokenResetPassword;

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordGenerated;
use AuthMicroservice\Authentication\Domain\Model\User\UserNotFoundException;
use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use AuthMicroservice\Shared\Domain\Service\EventDispatcher;
use AuthMicroservice\Shared\Domain\Service\RandomStringGenerator;

class GenerateTokenResetPassword
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
     */
    public function handle(GenerateTokenResetPasswordRequest $generateTokenResetPasswordRequest): void
    {
        $user = $this->userRepository->ofEmail($generateTokenResetPasswordRequest->email());

        try {
            $tokenResetPassword = $this->tokenResetPasswordRepository->ofEmail($generateTokenResetPasswordRequest->email());
            $tokenResetPassword->changeToken($this->randomStringGenerator->execute());
        } catch (TokenResetPasswordNotFoundException) {
            $tokenResetPassword = new TokenResetPassword($user->email(), $this->randomStringGenerator->execute());
        }

        $this->tokenResetPasswordRepository->persist($tokenResetPassword);

        $this->eventDispatcher->execute(new TokenResetPasswordGenerated($user, $tokenResetPassword));
    }
}
