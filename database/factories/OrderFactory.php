<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
       "name" => $faker->name,
       "user_id" => 1,
       "menu_id" => 1
    ];
});
