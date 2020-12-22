<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLcmInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcm_infos', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->unique();
            $table->unsignedBigInteger('lcm_model')->nullable();
            $table->timestamps();
            $table->foreign('lcm_model')->references('id')->on('lcm_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lcm_infos');
    }
}
