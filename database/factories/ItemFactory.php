<?php

use App\Input;
use Faker\Generator as Faker;

// generování záznamu v db pomocí pomocné funkce factory
$factory->define(App\Item::class, function (Faker $faker) {

    $input = null;
    while (true) {
        $rnd = mt_rand(1, config('app.inputs'));
        $input = Input::find($rnd);
        if ($input->inputType == "spinner") break;
    }

    return [
        'input_id' => $input->id,
        'text' => $faker->word,
    ];
});
