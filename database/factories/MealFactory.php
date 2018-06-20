<?php

use Faker\Generator as Faker;

$factory->define(App\Meal::class, function (Faker $faker) {
    return [
       
        'name' => $faker->name,
        'quantity' => 1500,
        'user_id' => 2,
        'picture' => $faker->name,
        
    ];
});
