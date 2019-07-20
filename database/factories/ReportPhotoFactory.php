<?php

use Faker\Generator as Faker;

// generování záznamu v db pomocí pomocné funkce factory
$factory->define(App\ReportPhoto::class, function (Faker $faker) {
    return [
        'report_id' => random_int(1, config('app.reports')),
        'url' => "https://res.cloudinary.com/kotik/image/upload/v1553604342/Reports/17309100_734348593399040_5374185289170273011_n.jpg",
    ];
});
