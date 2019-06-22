<?php

use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;

$factory->define(App\Territory::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
        'avatarURL' => "https://res.cloudinary.com/kotik/image/upload/v1560814782/Images/brno-stred.png",
        'admin_id' => mt_rand(1, config('app.users')),
    ];
});
