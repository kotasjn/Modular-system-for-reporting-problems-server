<?php

use Faker\Generator as Faker;

$factory->define(App\Input::class, function (Faker $faker) {

    $inputType = "";
    $characters = null;

    switch (mt_rand(1, 3)) {
        case 1:
            $inputType = "string";
            $characters = 255;
            break;
        case 2:
            $inputType = "number";
            break;
        case 3:
            $inputType = "spinner";
    }

    return [
        'module_id' => mt_rand(1, config('app.modules')),
        'inputType' => $inputType,
        'title' => "Název inputu",
        'characters' => $characters,
        'hint' => $faker->sentence,
    ];
});
