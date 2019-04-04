<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('avatarURL');
            $table->multipolygon('location', 4326);
            $table->unsignedInteger('approver_id')->nullable();
            $table->unsignedInteger('admin_id');

            $table->spatialIndex('location');

            $table->foreign('approver_id')->references('id')->on('users');
            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('territories', function (Blueprint $table) {
            $table->dropSpatialIndex(['location']);
        });

        Schema::dropIfExists('territories');
    }
}
