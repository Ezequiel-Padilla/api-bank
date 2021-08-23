<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source');
            $table->foreign('source')->references('id')->on('accounts');
            $table->unsignedBigInteger('destiny')->nullable();
            $table->foreign('destiny')->references('id')->on('accounts');
            $table->enum('type', ['Transferir','Retiro','Deposito']);
            $table->integer('amount');
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('events');
    }
}
