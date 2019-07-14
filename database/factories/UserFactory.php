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
        'name' => "Test",
        'email' => "test@test.cz",
        'email_verified_at' => now(),
        'avatarURL' => "https://res.cloudinary.com/kotik/image/upload/v1560814782/Images/default-profile.png",
        'telephone' => mt_rand(100000000,999999999),
        'password' => bcrypt('12345678'),
        'remember_token' => str_random(10),
    ];
});
