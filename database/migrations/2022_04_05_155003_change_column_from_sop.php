<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromSop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sop', function (Blueprint $table) {
            //
            $table->dropColumn('sop_nama');
            // $table->unsignedBigInteger('jenis_komoditas_id');
            // $table->foreign('jenis_komoditas_id')->references('id')->on('jenis_komoditas')->onDelete('cascade');
            // $table->dropColumn('jenis_komoditas_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sop', function (Blueprint $table) {
            //
        });
    }
}
