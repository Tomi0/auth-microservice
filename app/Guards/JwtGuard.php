<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Authentication\Domain\Model\User\InvalidJwtTokenException;
use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;

class JwtGuard implements Guard
{
    private User $user;

    /**
     * JWTGuard constructor.
     * @param Configuration $configuration
     * @param Request $request
     * @param UserRepository $userRepository
     * @throws InvalidJwtTokenException
     * @throws UserNotFoundException
     */
    public function __construct(Configuration $configuration, Request $request, UserRepository $userRepository)
    {
        $authorizationToken = $request->header('Authorization');

        if ($authorizationToken === null)
            return;

        if (!is_string($authorizationToken) || $authorizationToken === '')
            throw new InvalidJwtTokenException();

        $authorizationToken = str_replace('Bearer ', '', $authorizationToken);

        $constraints = $configuration->validationConstraints();

        /** @var UnencryptedToken $token */
        $token = $configuration->parser()->parse($authorizationToken);

        if (!$configuration->validator()->validate($token, ...$constraints))
            throw new InvalidJwtTokenException();

        $this->setUser($userRepository->ofId($token->claims()->get('jti')));
    }

    public function check(): bool
    {
        return isset($this->user);
    }

    public function guest()
    {
        // TODO: Implement guest() method.
    }

    /**
     * @return User|null
     */
    public function user(): User|null
    {
        return $this->user ?? null;
    }

    public function id(): UuidInterface|null
    {
        return isset($this->user) ? $this->user->id() : null;
    }

    public function validate(array $credentials = [])
    {
    }

    public function hasUser()
    {
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
