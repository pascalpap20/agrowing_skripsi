<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromTahapan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tahapan', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('sop_id');
            $table->unsignedBigInteger('admin_id');
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
        Schema::table('tahapan', function (Blueprint $table) {
            //
            $table->dropColumn('sop_id');
            $table->dropColumn('admin_id');
            $table->dropForeign(['sop_id']);
        });
    }
}
