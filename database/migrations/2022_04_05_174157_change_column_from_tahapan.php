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
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();
        Schema::table('tahapan', function (Blueprint $table) use ($driver) {
            //
            if($driver === 'sqlite'){
                $table->unsignedBigInteger('sop_id')->default('');
                $table->unsignedBigInteger('admin_id')->default('');
            } else {
                $table->unsignedBigInteger('sop_id');
                $table->unsignedBigInteger('admin_id');
            }
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
