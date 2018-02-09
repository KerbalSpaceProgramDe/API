<?php

use Faker\Generator as Faker;


$factory->define(App\Shoutbox::class, function (Faker $faker) {
    return [
        'userID' => $faker->numberBetween(10,1000),
        'username' => $faker->name,
        'time' => $faker->unixTime(),
        'message' => $faker->sentence,
        'ipAddress' => $faker->ipv4,
        'fromApi' => $faker->boolean()
    ];
});
