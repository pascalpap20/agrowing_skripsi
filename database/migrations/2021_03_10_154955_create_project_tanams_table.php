<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTanamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::create('project_tanam', function (Blueprint $table) use ($driver){
            $table->id();
            $table->unsignedBigInteger('manager_kebun_id');
            $table->unsignedBigInteger('sop_id');
            $table->unsignedBigInteger('alamat_id');
            $table->string('status');
            $table->timestamps();
            if($driver === 'sqlite'){
                $table->unsignedBigInteger('tahapan_sop_id')->nullable();
            } else {
                $table->unsignedBigInteger('tahapan_sop_id');
            }
            $table->foreign('tahapan_sop_id')->references('id')->on('tahapan')->onDelete('cascade');
            $table->foreign('manager_kebun_id')->references('id')->on('manager_kebun')->onDelete('cascade');
            $table->foreign('sop_id')->references('id')->on('sop')->onDelete('cascade');
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
        Schema::dropIfExists('project_tanam');
    }
}
