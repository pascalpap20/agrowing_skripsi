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
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();
        if($driver == 'sqlite'){
            DB::statement("ALTER TABLE catat_item ADD COLUMN 'item_pekerjaan_id' integer;");
            Schema::table('catat_item', function (Blueprint $table) use ($driver) {
                //
                $table->dropColumn('item_pekerjaan');
            });
        } else {
            Schema::table('catat_item', function (Blueprint $table) use ($driver) {
                //
                $table->dropColumn('item_pekerjaan');
                if ($driver === 'sqlite'){
                    $table->unsignedBigInteger('item_pekerjaan_id')->default('');
                } else {
                    $table->unsignedBigInteger('item_pekerjaan_id');
                }
                $table->foreign('item_pekerjaan_id')->references('id')->on('item_pekerjaan')->onDelete('cascade');
            });
        }
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
