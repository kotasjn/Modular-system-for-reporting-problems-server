<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('data');

            $table->unsignedInteger('module_id');
            $table->unsignedInteger('report_id');

            $table->foreign('module_id')->references('id')->on('modules')->onDelere('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_datas');
    }
}
