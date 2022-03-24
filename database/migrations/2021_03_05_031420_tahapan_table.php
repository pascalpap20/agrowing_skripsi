<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TahapanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          //atribut tabel
          Schema::create('tahapan', function(Blueprint $table){
            $table-> id();
            $table-> string('nama_tahapan');
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
        Schema::dropIfExists('tahapan');

    }
}
