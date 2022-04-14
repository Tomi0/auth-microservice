<?php

use AuthMicroservice\Authentication\Domain\Model\User\User;

$factory->define(User::class, function(Faker\Generator $faker) {
    return [
        'email' => $faker->email(),
        'password' => \Illuminate\Support\Facades\Hash::make($faker->password()),
        'admin' => $faker->boolean(),
    ];
});
