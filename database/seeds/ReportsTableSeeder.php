<?php

use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model Report. Vygeneruje se počet záznamů, který je uveden v konfiguračním souboru.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Report', config('app.reports'))->create();
    }
}
