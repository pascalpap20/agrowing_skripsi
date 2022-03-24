<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //atribut tabel
        Schema::create('item_pekerjaan', function(Blueprint $table){
            $table-> id();
            $table-> unsignedBigInteger('sop_id');
            $table-> unsignedBigInteger('tahapan_sop_id');
            $table-> string('nama_kegiatan');
            $table-> string('durasi_waktu');
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
         Schema::dropIfExists('item_pekerjaan');
    }
}
