<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatIndikatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catat_indikator', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catat_item_id');
            $table->unsignedBigInteger('indikator_id');
            $table->string('nama_indikator');
            $table->string('catat_jawaban');

            $table->foreign('catat_item_id')->references('id')->on('catat_item')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catat_indikator');
    }
}
