<?php

use Faker\Generator as Faker;

$factory->define(App\UserType::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
