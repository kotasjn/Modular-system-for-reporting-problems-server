<?php

use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(App\Report::class, function (Faker $faker) {
    $userNote = $faker->paragraph(3);
    $employeeNote = $faker->paragraph(2);
    return [
        'title' => $faker->title,
        'state' => mt_rand(0, 3),
        'user_id' => mt_rand(1, 10),
        'category_id' => mt_rand(1, 10),
        'territory_id' => mt_rand(1, 10),
        'location' => new Point(mt_rand(-8500000, 8500000)/100000,mt_rand(-18000000, 18000000)/100000),
        'userNote' => strlen($userNote) > 255 ? substr($userNote,0,252)."..." : $userNote,
        'EmployeeNote' => strlen($employeeNote) > 255 ? substr($employeeNote,0,252)."..." : $employeeNote,
        'address' => $faker->address,
    ];
});
