<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->boolean('recurent')->default(false);
            $table->integer('duration')->nullable();
            $table->enum('duration_unit', ['minutes', 'hours', 'days', 'weeks', 'months', 'years'])->nullable();
            $table->datetime('due_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
