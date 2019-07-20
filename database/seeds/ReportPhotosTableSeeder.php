<?php

use Illuminate\Database\Seeder;

class ReportPhotosTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model ReportPhoto. Vygeneruje se počet záznamů, který je uveden v konfiguračním souboru.
     *
     * @return void
     */
    public function run()
    {
        factory('App\ReportPhoto', config('app.report_photos'))->create();
    }
}
