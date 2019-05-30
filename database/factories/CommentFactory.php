<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    $comment = $faker->paragraph(5);
    return [
        'user_id' => mt_rand(1, config('app.users')),
        'report_id' => mt_rand(1, config('app.reports')),
        'text' => strlen($comment) > 511 ? substr($comment,0,252)."..." : $comment,
    ];
});