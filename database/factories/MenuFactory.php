<?php

use Faker\Generator as Faker;

$factory->define(App\Menu::class, function (Faker $faker) {
    $datePerso = [
        '2018-06-18',
        '2018-06-20',
        '2018-06-22',
        '2018-06-24',
        '2018-06-26',
        '2018-06-28',
        '2018-06-30',
        '2018-07-10',
        '2018-07-18',
        '2018-07-20',
        '2018-07-19'

    ];

    return [
        "user_id" => $faker->numberBetween(1,3),
        "meal_id" => $faker->numberBetween(1,17),
        "date"=>$datePerso[$faker->numberBetween(0,7)],
    ];
});
