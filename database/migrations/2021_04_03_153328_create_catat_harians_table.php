<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatHariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catat_harian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blok_lahan_id');
            $table->unsignedBigInteger('tahapan_id');
            $table->string('catatan')->nullable();
            $table->timestamps();

            $table->foreign('blok_lahan_id')->references('id')->on('blok_lahan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catat_harian');
    }
}
