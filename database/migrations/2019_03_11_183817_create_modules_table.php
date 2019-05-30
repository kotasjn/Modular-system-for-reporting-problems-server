<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->unique();
            $table->boolean('active')->default(false);

            $table->unsignedInteger('category_id');
            $table->unsignedInteger('territory_id');

            $table->foreign('category_id')->references('id')->on('categories')->onDelere('cascade');
            $table->foreign('territory_id')->references('id')->on('territories')->onDelere('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
