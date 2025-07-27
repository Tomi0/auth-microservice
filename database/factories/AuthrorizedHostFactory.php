<?php


use Authentication\Domain\Model\Client\Client;

$factory->define(Client::class, function(Faker\Generator $faker) {
    return [
        'hostName' => $faker->domainName(),
    ];
});
