<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableCatatIndikator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catat_indikator', function (Blueprint $table) {
            //
            $table->dropColumn('nama_indikator');
            $table->foreign('indikator_id')->references('id')->on('indikator_kegiatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catat_indikator', function (Blueprint $table) {
            //
            $table->string('nama_indikator');
            $table->dropForeign(['indikator_id']);
        });
    }
}
