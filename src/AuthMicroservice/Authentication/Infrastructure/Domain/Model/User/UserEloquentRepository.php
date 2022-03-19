<?php

namespace AuthMicroservice\Authentication\Infrastructure\Domain\Model\User;

use AuthMicroservice\Authentication\Domain\Model\User\UserRepository;
use UnexpectedValueException;

class UserEloquentRepository implements UserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $attributes): string
    {
        $attributes['password'] = bcrypt($attributes['password']);

        $user = $this->user->create($attributes);

        if (isset($user['id']) && $user['id']) {
            return $user['id'];
        }

        throw new UnexpectedValueException('No se ha detectado el id del usuario creado');
    }

    public function ofEmailOrFail(string $email): object
    {
        return $this->user->where('email', '=', $email)->firstOrFail();
    }
}
