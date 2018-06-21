<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
       "name" => "Order n ".$faker->numberBetween(1,5),
       "user_id" => $faker->numberBetween(1,3),
       "menu_id" => $faker->numberBetween(1,10)
    ];
});
