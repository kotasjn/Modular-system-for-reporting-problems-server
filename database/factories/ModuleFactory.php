<?php

use Faker\Generator as Faker;

$factory->define(App\Module::class, function (Faker $faker) {
    $territory = 1;

    return [
        'category_id' => mt_rand(1, config('app.categories')),
        'territory_id' => $territory,
        'name' => $faker->unique()->word,
        'active' => mt_rand(0,1) ? true : false,
    ];
});
