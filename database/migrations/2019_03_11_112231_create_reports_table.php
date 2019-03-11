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
            $table->timestamp('lastChange');
            $table->string('title');
            $table->string('state');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('userNote');
            $table->string('employeeNote');
            $table->string('address');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('responsible_user_id')->nullable();
            $table->unsignedInteger('category_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('responsible_user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');


            //TODO territoryID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
