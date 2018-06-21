<?php

use Faker\Generator as Faker;

$factory->define(App\Meal::class, function (Faker $faker) {

    $mealName = [
        'Steak Frites',
        'Chao Men',
        'Maa tinto',
        'Boeuf au légumes',
        'Poke poisson',
        'Sashimi',
        'Carpaccio de thon',
        'Carpaccio de boeuf'
    ];

    $mealPicture = [
        'https://media.blueapron.com/recipes/2121/c_main_dish_images/1490637097-4-0002-2195/403_2PRE07-steak-frites-18311_WEB_Center_high_feature.jpg',
        'http://static.750g.com/images/600-600/7f01f2ee95a47f169599e8aa3f9e4146/recette-tahitienne-le-chao-men.jpg',
    ];

    return [
       
        'name' => $mealName[$faker->numberBetween(0,7)],
        'quantity' => $faker->numberBetween(1,7),
        'user_id' => $faker->numberBetween(1,3),
        'picture' => $mealPicture[$faker->numberBetween(0,1)],
        
    ];
});
