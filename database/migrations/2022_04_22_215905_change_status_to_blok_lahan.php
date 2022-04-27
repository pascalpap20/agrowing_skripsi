<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusToBlokLahan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();
        Schema::table('blok_lahan', function (Blueprint $table) use ($driver){
            //
            if($driver !== 'sqlite'){
                $table->string('status')->default('belum selesai')->change();
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
            $table->dropColumn('status');
        });
    }
}
