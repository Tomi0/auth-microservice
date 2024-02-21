<?php

use Illuminate\Support\Facades\Hash;
use Authentication\Domain\Model\User\User;

$factory->define(User::class, function(Faker\Generator $faker) {
    return [
        'fullName' => $faker->name(),
        'email' => $faker->email(),
        'password' => Hash::make($faker->password()),
        'admin' => $faker->boolean(),
    ];
});
