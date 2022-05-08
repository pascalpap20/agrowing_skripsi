<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::create('districts', function(Blueprint $table) use ($driver){
            if($driver === 'sqlite'){
                $table->id()->default('');
                $table->char('regency_id', 4)->default('');
                $table->string('name', 50)->default('');
            } else {
                $table->char('id', 7)->index();
                $table->char('regency_id', 4);
                $table->string('name', 50);
            }
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('districts');
    }
}
