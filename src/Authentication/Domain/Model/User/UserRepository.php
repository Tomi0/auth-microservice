<?php

namespace Authentication\Domain\Model\User;

interface UserRepository
{
    public function create(array $attributes): string;
}
