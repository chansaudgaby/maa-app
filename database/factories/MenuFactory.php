<?php

use Faker\Generator as Faker;

$factory->define(App\Menu::class, function (Faker $faker) {
    return [
        "user_id" => $faker->randomDigit,
        "meal_id" => $faker->randomDigit,
        "date"=>$faker->date('Y-m-d'),
    ];
});
