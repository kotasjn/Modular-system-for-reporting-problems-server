<?php

use App\Territory;
use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(App\Report::class, function (Faker $faker) {
    $userNote = $faker->paragraph(3);
    $employeeNote = $faker->paragraph(2);
    $territory = 1;

    return [
        'title' => "Nejaky random nadpis...",
        'state' => mt_rand(0, 3),
        'user_id' => mt_rand(1, config('app.users')),
        'category_id' => mt_rand(1, config('app.categories')),
        'territory_id' => $territory,
        'location' => Point::fromWKT('SRID=0;POINT(' . strval(mt_rand(-9000000, 9000000)/100000) . ' ' . strval(mt_rand(-18000000, 18000000)/100000) . ')'),
        'userNote' => strlen($userNote) > 255 ? substr($userNote,0,252)."..." : $userNote,
        'EmployeeNote' => strlen($employeeNote) > 255 ? substr($employeeNote,0,252)."..." : $employeeNote,
        'address' => Territory::where('id', $territory)->first()->name,
    ];
});
