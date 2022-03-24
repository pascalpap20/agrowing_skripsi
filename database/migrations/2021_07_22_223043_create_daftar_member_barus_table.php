<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarMemberBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_member_baru', function (Blueprint $table) {
            $table->id();
            $table-> string('nama');
            $table-> char('jenis_kelamin');
            $table-> string('no_hp');
            $table-> string('email')->unique();
            $table-> unsignedBigInteger('alamat_id');
            $table-> string('status')->default('belum diproses');
            $table-> timestamps();

            
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
        Schema::dropIfExists('daftar_member_barus');
    }
}
