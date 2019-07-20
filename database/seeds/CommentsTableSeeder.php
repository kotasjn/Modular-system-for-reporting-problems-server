<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model Comment. Vygeneruje se počet záznamů, který je uveden v konfiguračním souboru.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Comment', config('app.comments'))->create();
    }
}
