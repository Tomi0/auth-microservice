<?php

namespace Authentication\Application\Service\TokenResetPassword;

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordGenerated;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordNotFoundException;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPasswordRepository;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Shared\Domain\Service\RandomStringGenerator;

class GenerateTokenResetPassword
{
    private TokenResetPasswordRepository $tokenResetPasswordRepository;
    private UserRepository $userRepository;
    private RandomStringGenerator $randomStringGenerator;

    public function __construct(TokenResetPasswordRepository $tokenResetPasswordRepository,
                                UserRepository               $userRepository,
                                RandomStringGenerator        $randomStringGenerator)
    {
        $this->tokenResetPasswordRepository = $tokenResetPasswordRepository;
        $this->userRepository = $userRepository;
        $this->randomStringGenerator = $randomStringGenerator;
    }

    /**
     * @throws UserNotFoundException
     */
    public function handle(GenerateTokenResetPasswordRequest $generateTokenResetPasswordRequest): void
    {
        $user = $this->userRepository->ofEmail($generateTokenResetPasswordRequest->email);

        try {
            $tokenResetPassword = $this->tokenResetPasswordRepository->ofEmail($generateTokenResetPasswordRequest->email);
            $tokenResetPassword->changeToken($this->randomStringGenerator->execute());
        } catch (TokenResetPasswordNotFoundException) {
            $tokenResetPassword = new TokenResetPassword($user, $this->randomStringGenerator->execute());
        }

        $this->tokenResetPasswordRepository->persist($tokenResetPassword);
    }
}
