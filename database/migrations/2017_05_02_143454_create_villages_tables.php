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

class CreateVillagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::create('villages', function(Blueprint $table) use ($driver){
            if($driver === 'sqlite'){
                $table->id()->default('');
                $table->char('district_id', 7)->default('');
                $table->string('name', 50)->default('');
            } else {
                $table->char('id', 10)->index();
                $table->char('district_id', 7);
                $table->string('name', 50);        
            }
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
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
        Schema::drop('villages');
    }
}
