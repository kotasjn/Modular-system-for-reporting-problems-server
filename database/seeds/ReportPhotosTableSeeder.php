<?php

use Illuminate\Database\Seeder;

class ReportPhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\ReportPhoto', config('app.report_photos'))->create();
    }
}
