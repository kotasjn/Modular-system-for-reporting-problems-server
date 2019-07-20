<?php

use Illuminate\Database\Seeder;

class InputTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model Input. Vygeneruje se počet záznamů, který je uveden v konfiguračním souboru.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Input', config('app.inputs'))->create();
    }
}
