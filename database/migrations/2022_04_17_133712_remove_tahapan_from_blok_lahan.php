<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTahapanFromBlokLahan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();
        Schema::table('blok_lahan', function (Blueprint $table) use ($driver) {
            //
            if($driver !== 'sqlite'){
                $table->dropForeign(['tahapan_id']);
                $table->dropColumn('tahapan_id');
            }
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
            //
            $table->unsignedBigInteger('tahapan_id');
            $table->foreign('tahapan_id')->references('id')->on('tahapan');
        });
    }
}
