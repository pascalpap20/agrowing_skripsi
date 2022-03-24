<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ManagerKebunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //atribut tabel
        Schema::create('manager_kebun', function(Blueprint $table){
            $table-> id();
            $table->unsignedBigInteger('user_id');
            $table-> string('nama');
            $table-> char('jenis_kelamin');
            $table-> string('no_hp');
            $table-> string('email')->unique();
            $table-> unsignedBigInteger('alamat_id');
            $table-> string('foto')->nullable();
            $table-> timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('alamat_id')->references('id')->on('alamat')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_kebun');

    }
}
