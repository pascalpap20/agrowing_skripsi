<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlokLahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blok_lahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_tanam_id');
            $table->float('luas_blok');
            $table->integer('periode')->default('1');
            $table->integer('jumlah_tanaman');
            $table->integer('umur_tanaman');

            $table->foreign('project_tanam_id')->references('id')->on('project_tanam')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blok_lahan');
    }
}
