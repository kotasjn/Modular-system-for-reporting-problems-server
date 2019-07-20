<?php

use Illuminate\Database\Seeder;

class ItemTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model Item. Vygeneruje se počet záznamů, který je uveden v konfiguračním souboru.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Item', config('app.items'))->create();
    }
}
