<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBugsTable extends Migration
{
    /**
     * Spuštění migrací
     *
     * @return void
     */
    public function up()
    {
        // vytvoření tabulky pro nahlášené chyby systému
        Schema::create('bugs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Vrácení migrací
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bugs');
    }
}
