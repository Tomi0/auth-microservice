<?php

use Authentication\Domain\Model\TokenResetPassword\TokenResetPassword;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

$factory->define(TokenResetPassword::class, function(Faker\Generator $faker) {
    return [
        'id' => Uuid::uuid4(),
        'email' => $faker->email(),
        'token' => Str::random(),
        'createdAt' => new DateTime(),
        'updatedAt' => new DateTime(),
    ];
});
