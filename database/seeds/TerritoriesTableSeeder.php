<?php

use Illuminate\Database\Seeder;

class TerritoriesTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model Territory. Vygeneruje se pouze jeden záznam.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Territory', 1)->create();
    }
}
