<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          //atribut tabel
          Schema::create('admin', function(Blueprint $table){
            $table-> id();
            $table->unsignedBigInteger('user_id');
            $table-> string('nama');
            $table-> timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         //drop tabel
         Schema::dropIfExists('admin');
    }
}
