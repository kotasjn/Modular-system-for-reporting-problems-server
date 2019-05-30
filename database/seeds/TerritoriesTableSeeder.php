<?php

use Illuminate\Database\Seeder;

class TerritoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Territory', 1)->create();
    }
}
