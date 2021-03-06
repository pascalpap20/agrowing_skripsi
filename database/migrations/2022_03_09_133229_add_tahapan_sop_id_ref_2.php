<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahapanSopIdRef2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::table('project_tanam', function (Blueprint $table) {
            //
                $table->unsignedBigInteger('tahapan_sop_id')->change();
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
        Schema::table('project_tanam', function (Blueprint $table) {
            //
            $table->dropColumn('tahapan_sop_id');
        });
    }
}
