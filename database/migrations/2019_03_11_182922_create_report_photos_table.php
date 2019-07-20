<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportPhotosTable extends Migration
{
    /**
     * Spuštění migrací
     *
     * @return void
     */
    public function up()
    {
        // vytvoření tabulky pro fotografie místa nebo předmětu hlášení
        Schema::create('report_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('url');

            $table->unsignedInteger('report_id');

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Vrácení migrací
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_photos');
    }
}
