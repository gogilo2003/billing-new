<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id');
            $table->string('message');
            $table->string('recepient');
            $table->string('send_time')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('status')->nullable();
            $table->string('sms_unit')->nullable();
            $table->string('network_name')->nullable();
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
        Schema::dropIfExists('sms');
    }
}
