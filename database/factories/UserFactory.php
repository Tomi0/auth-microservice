<?php

use Authentication\Domain\Model\User\User;
use Illuminate\Support\Facades\Hash;

$factory->define(User::class, function(Faker\Generator $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4(),
        'fullName' => $faker->name(),
        'email' => $faker->email(),
        'password' => Hash::make($faker->password()),
        'admin' => $faker->boolean(),
    ];
});
