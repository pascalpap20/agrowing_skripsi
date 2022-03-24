<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //atribut tabel
        Schema::create('sop', function(Blueprint $table){
            $table-> id();
            $table-> unsignedBigInteger('admin_id');
            $table-> string('sop_nama');
            $table-> string('estimasi_panen');
            $table-> string('deskripsi');
            $table-> string('foto')->nullable();
            $table-> integer('kalkulasi_waktu_panen');
            $table-> float('kalkulasi_bobot_panen');
            $table-> timestamps();

        });

        // Schema::table('sop', function($table){
        //     $table->foreign('admin_id')
        //         ->references('admin_id')->on('admin')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         //drop tabel
         Schema::dropIfExists('sop');
    }
}
