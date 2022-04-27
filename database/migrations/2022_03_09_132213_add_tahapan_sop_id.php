<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahapanSopId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::table('project_tanam', function (Blueprint $table) use ($driver){
            //
            if($driver !== 'sqlite'){
                $table->unsignedBigInteger('tahapan_sop_id');
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
        Schema::table('project_tanam', function (Blueprint $table) {
            //
            $table->dropColumn('tahapan_sop_id');
        });
    }
}
