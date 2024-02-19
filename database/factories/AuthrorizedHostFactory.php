<?php


use Authentication\Domain\Model\AuthorizedHost\AuthorizedHost;

$factory->define(AuthorizedHost::class, function(Faker\Generator $faker) {
    return [
        'hostName' => $faker->domainName(),
    ];
});
