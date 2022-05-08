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

class CreateRegenciesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::create('regencies', function(Blueprint $table) use($driver) {
            if($driver === 'sqlite'){
                $table->id()->default('');
                $table->char('province_id', 2)->default('');
                $table->string('name', 50)->default('');
            } else {
                $table->char('id', 4)->index();
                $table->char('province_id', 2);
                $table->string('name', 50);
            }
            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
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
        Schema::drop('regencies');
    }
}
