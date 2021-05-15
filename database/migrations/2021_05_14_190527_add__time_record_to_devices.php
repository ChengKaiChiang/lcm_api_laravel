<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeRecordToDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            //
            $table->timestamp('send_mqtt_at', 6)->nullable();
            $table->timestamp('receive_mqtt_at', 6)->nullable();
            $table->timestamp('start_download_at', 6)->nullable();
            $table->timestamp('end_download_at', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            //
            $table->dropColumn('send_mqtt_at');
            $table->dropColumn('receive_mqtt_at');
            $table->dropColumn('start_download_at');
            $table->dropColumn('end_download_at');
        });
    }
}
