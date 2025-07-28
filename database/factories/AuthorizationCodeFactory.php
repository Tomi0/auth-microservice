<?php

use Authentication\Domain\Model\AuthorizationCode\AuthorizationCode;

$factory->define(AuthorizationCode::class, function (Faker\Generator $faker) {
    $uuid = \Ramsey\Uuid\Uuid::uuid4();
    return [
        'id' => $uuid,
        'clientId' => \Ramsey\Uuid\Uuid::uuid4(),
        'userId' => \Ramsey\Uuid\Uuid::uuid4(),
        'code' => $uuid . '-' . $faker->asciify(),
        'expiresAt' => new DateTimeImmutable('+1 hour'),
        'createdAt' => new DateTimeImmutable(),
        'updatedAt' => new DateTimeImmutable(),
    ];
});
