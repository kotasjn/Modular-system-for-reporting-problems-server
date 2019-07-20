<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoriesTable extends Migration
{
    /**
     * Spuštění migrací
     *
     * @return void
     */
    public function up()
    {
        // vytvoření tabulky pro samosprávy
        Schema::create('territories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('avatarURL');
            $table->unsignedInteger('approver_id')->nullable();
            $table->unsignedInteger('admin_id');

            $table->foreign('approver_id')->references('id')->on('users');
            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Vrácení migrací
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('territories', function (Blueprint $table) {
            $table->dropSpatialIndex(['location']);
        });
        */

        Schema::dropIfExists('territories');
    }
}
