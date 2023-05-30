<?php

use Illuminate\Support\Str;
use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;

$factory->define(TokenResetPassword::class, function(Faker\Generator $faker) {
    return [
        'email' => $faker->email(),
        'token' => Str::random(),
    ];
});
