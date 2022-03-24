<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTahapanToBlokLahan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blok_lahan', function (Blueprint $table) {
            $table->unsignedBigInteger('tahapan_id');
            $table->foreign('tahapan_id')->references('id')->on('tahapan');
        });

        Schema::table('project_tanam', function (Blueprint $table) {
            $table->dropForeign(['tahapan_sop_id']);
            $table->dropColumn(['tahapan_sop_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blok_lahan', function (Blueprint $table) {
            $table->dropForeign(['tahapan_id']);
            $table->dropColumn(['tahapan_id']);
        });

        Schema::table('project_tanam', function (Blueprint $table) {
            $table->unsignedBigInteger('tahapan_id');
            $table->foreign('tahapan_id')->references('id')->on('tahapan');
        });
    }
}
