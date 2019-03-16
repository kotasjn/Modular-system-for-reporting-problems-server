<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'avatarURL' => "https://res.cloudinary.com/kotik/image/upload/v1548981829/Images/default-profile.jpg",
        'telephone' => mt_rand(100000000,999999999),
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});
