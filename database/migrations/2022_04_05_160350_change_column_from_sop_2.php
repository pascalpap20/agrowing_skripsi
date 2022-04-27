<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromSop2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();
        Schema::table('sop', function (Blueprint $table) use ($driver){
            //
            if($driver === 'sqlite'){
                $table->unsignedBigInteger('jenis_komoditas_id')->default('');
            } else {
                $table->unsignedBigInteger('jenis_komoditas_id');
            }
            $table->foreign('jenis_komoditas_id')->references('id')->on('jenis_komoditas')->onDelete('cascade');
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
