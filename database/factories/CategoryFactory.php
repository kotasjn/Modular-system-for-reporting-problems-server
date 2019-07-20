<?php

use Faker\Generator as Faker;

// generování záznamu v db pomocí pomocné funkce factory
$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->md5,
    ];
});
