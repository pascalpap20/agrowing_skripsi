<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignFromItemPekerjaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_pekerjaan', function (Blueprint $table) {
            //
            $table->foreign('tahapan_sop_id')->references('id')->on('tahapan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_pekerjaan', function (Blueprint $table) {
            //
            $table->dropForeign(['tahapan_sop_id']);
        });
    }
}
