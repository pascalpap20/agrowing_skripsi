<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catat_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catat_harian_id');
            $table->string('item_pekerjaan');
            $table->tinyInteger('filled')->default('0');

            $table->foreign('catat_harian_id')->references('id')->on('catat_harian')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catat_item');
    }
}
