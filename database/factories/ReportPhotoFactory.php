<?php

use Faker\Generator as Faker;

$factory->define(App\ReportPhoto::class, function (Faker $faker) {
    return [
        'report_id' => random_int(1,50),
        'url' => "https://res.cloudinary.com/kotik/image/upload/v1553604342/Reports/17309100_734348593399040_5374185289170273011_n.jpg",
    ];
});
