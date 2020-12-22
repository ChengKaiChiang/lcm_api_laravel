<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLcmStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcm_status', function (Blueprint $table) {            
            $table->string('lcm_ip');
            $table->string('lcm_mac');
            $table->string('color');
            $table->double('lcm_power')->nullable();
            $table->double('lcm_current')->nullable();
            $table->double('backlight_power')->nullable();
            $table->double('backlight_current')->nullable();
            $table->timestamps();
            $table->primary(['lcm_ip', 'color']);
            $table->foreign('lcm_ip')->references('ip')->on('lcm_infos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lcm_status');
    }
}
