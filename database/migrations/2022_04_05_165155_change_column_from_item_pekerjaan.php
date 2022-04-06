<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromItemPekerjaan extends Migration
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
            $table->foreign('sop_id')->references('id')->on('sop')->onDelete('cascade');
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
            $table->dropForeign(['sop_id']);
        });
    }
}
