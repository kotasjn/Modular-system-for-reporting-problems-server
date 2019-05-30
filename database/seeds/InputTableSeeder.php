<?php

use Illuminate\Database\Seeder;

class InputTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Input', config('app.inputs'))->create();
    }
}
