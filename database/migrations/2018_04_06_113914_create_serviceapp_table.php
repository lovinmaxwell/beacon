<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceappTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceapp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('SCANNERID');
            $table->string('UUID');
            $table->string('MAC');
            $table->string('RSSI');
            $table->TIMESTAMP('TIME');
            $table->string('LATITUDE');
            $table->string('LONGITUDE');
            $table->string('MAJOR_VALUE')->nullable();
            $table->string('MINOR_VALUE')->nullable();
            $table->string('MEASURED_POWER')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serviceapp');
    }
}
