<?php

namespace Authentication\Infrastructure\Laravel\Domain\Model\User;

use Authentication\Domain\Model\User\User;
use Authentication\Domain\Model\User\UserNotFoundException;
use Authentication\Domain\Model\User\UserRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserInMemoryRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users = [];

    public function persist(User $user): void
    {
        $this->users[] = $user;
    }

    public function ofEmail(string $email): User
    {
        foreach ($this->users as $user) {
            if ($user->email() === $email) {
                return $user;
            }
        }
        throw new UserNotFoundException();
    }

    public function ofId(string $userId): User
    {
        $userId = Uuid::fromString($userId);
        foreach ($this->users as $user) {
            if ($userId->equals($user->id())) {
                return $user;
            }
        }
        throw new UserNotFoundException();
    }

    public function remove(User $user): void
    {
        foreach ($this->users as $key => $value) {
            if ($value->id() === $user->id()) {
                unset($this->users[$key]);
            }
        }
    }

    public function search(array $filters): array
    {
        return $this->users;
    }

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
