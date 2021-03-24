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
            $table->string('position');
            $table->string('color');
            $table->double('lcm_power')->nullable();
            $table->double('lcm_current')->nullable();
            $table->double('backlight_power')->nullable();
            $table->double('backlight_current')->nullable();
            $table->timestamps();
            $table->primary(['device', 'position', 'color']);
            $table->foreign(['device', 'position'])->references(['device', 'position'])->on('devices')->onDelete('cascade')->onUpdate('cascade');
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
