<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisorsTable extends Migration
{
    /**
     * Spuštění migrací
     *
     * @return void
     */
    public function up()
    {
        // vytvoření tabulky pro supervisory
        Schema::create('supervisors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('territory_id');
            $table->unsignedInteger('user_id');

            $table->unique(['user_id', 'territory_id']);

            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Vrácení migrací
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supervisors');
    }
}
