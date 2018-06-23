<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
       "user_id" => 2,
       'quantity' => $faker->numberBetween(1,2),
       "menu_id" => $faker->numberBetween(1,5)
    ];
});
