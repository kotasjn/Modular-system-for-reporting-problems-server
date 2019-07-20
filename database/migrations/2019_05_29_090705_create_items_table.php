<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Spuštění migrací
     *
     * @return void
     */
    public function up()
    {
        // vytvoření tabulky pro položky seznamu spinneru/selectboxu
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('input_id');
            $table->string('text');

            $table->foreign('input_id')->references('id')->on('inputs')->onDelete('cascade');
        });
    }

    /**
     * Vrácení migrací
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
