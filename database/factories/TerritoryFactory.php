<?php

use Faker\Generator as Faker;

$factory->define(App\Territory::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'avatarURL' => "https://res.cloudinary.com/kotik/image/upload/v1548981829/Images/default-profile.jpg",
        'approver_id' => mt_rand(1,10),
        'admin_id' => mt_rand(1,10),
    ];
});
