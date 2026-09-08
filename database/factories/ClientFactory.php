<?php


use Authentication\Domain\Model\Client\Client;

$factory->define(Client::class, function (Faker\Generator $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'name' => $faker->slug(),
        'clientSecret' => $faker->randomAscii(),
        'redirectUrl' => $faker->domainName() . '/callback',
        'signingKeyId' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
    ];
});
