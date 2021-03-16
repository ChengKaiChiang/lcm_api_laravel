<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLcmDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcm_datas', function (Blueprint $table) {
            $table->string('device');
            $table->string('color');
            $table->double('lcm_power')->nullable();
            $table->double('lcm_current')->nullable();
            $table->double('backlight_power')->nullable();
            $table->double('backlight_current')->nullable();
            $table->timestamps();
            $table->primary(['device', 'color']);
            $table->foreign('device')->references('device')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lcm_datas');
    }
}
