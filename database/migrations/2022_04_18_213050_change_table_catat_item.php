<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableCatatItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catat_item', function (Blueprint $table) {
            //
            $table->dropColumn('item_pekerjaan');
            $table->unsignedBigInteger('item_pekerjaan_id');
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
        Schema::table('catat_item', function (Blueprint $table) {
            //
            $table->string('item_pekerjaan');
            $table->dropForeign(['item_pekerjaan_id']);
            $table->dropColumn('item_pekerjaan_id');
        });
    }
}
