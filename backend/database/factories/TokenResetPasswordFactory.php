<?php

use AuthMicroservice\Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Illuminate\Support\Str;

$factory->define(TokenResetPassword::class, function(Faker\Generator $faker) {
    return [
        'email' => $faker->email(),
        'token' => Str::random(),
    ];
});
