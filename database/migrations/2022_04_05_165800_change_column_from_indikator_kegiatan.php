<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromIndikatorKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indikator_kegiatan', function (Blueprint $table) {
            //
            $table->foreign('item_pekerjaan_id')->references('id')->on('item_pekerjaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indikator_kegiatan', function (Blueprint $table) {
            //
            $table->dropForeign(['item_pekerjaan_id']);
        });
    }
}
