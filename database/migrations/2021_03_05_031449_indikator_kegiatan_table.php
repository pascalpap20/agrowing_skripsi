<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndikatorKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          //atribut tabel
          Schema::create('indikator_kegiatan', function(Blueprint $table){
            $table-> id();
            $table-> unsignedBigInteger('item_pekerjaan_id');
            $table-> unsignedBigInteger('tipe_jawaban_id');
            $table-> string('nama_indikator');

            $table->foreign('tipe_jawaban_id')->references('id')->on('tipe_jawaban')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop tabel
        Schema::dropIfExists('indikator_kegiatan');
    }
}
