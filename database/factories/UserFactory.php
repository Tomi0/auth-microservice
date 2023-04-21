<?php

use AuthMicroservice\Authentication\Domain\Model\User\User;
use Illuminate\Support\Facades\Hash;

$factory->define(User::class, function(Faker\Generator $faker) {
    return [
        'email' => $faker->email(),
        'password' => Hash::make($faker->password()),
        'admin' => $faker->boolean(),
    ];
});
