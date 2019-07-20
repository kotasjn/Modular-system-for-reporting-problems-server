<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputDataTable extends Migration
{
    /**
     * Spuštění migrací
     *
     * @return void
     */
    public function up()
    {
        // vytvoření tabulky pro data vstupů
        Schema::create('input_data', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('data');

            $table->unsignedInteger('input_id');
            $table->unsignedInteger('module_data_id');

            $table->foreign('input_id')->references('id')->on('inputs')->onDelete('cascade');
            $table->foreign('module_data_id')->references('id')->on('module_data')->onDelete('cascade');
        });
    }

    /**
     * Vrácení migrací
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_data');
    }
}
