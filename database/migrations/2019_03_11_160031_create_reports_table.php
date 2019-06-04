<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title');
            $table->string('state')->default(0);
            $table->point('location', 0); // change to 4326
            $table->string('userNote')->nullable();
            $table->string('employeeNote')->nullable();
            $table->string('address')->nullable();

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('responsible_user_id')->nullable();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('territory_id')->default(1);

            $table->spatialIndex('location');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('responsible_user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropSpatialIndex(['location']);
        });

        Schema::dropIfExists('reports');
    }
}
