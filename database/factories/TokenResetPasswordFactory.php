<?php

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Illuminate\Support\Str;

$factory->define(TokenResetPassword::class, function(Faker\Generator $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4(),
        'email' => $faker->email(),
        'token' => Str::random(),
    ];
});
