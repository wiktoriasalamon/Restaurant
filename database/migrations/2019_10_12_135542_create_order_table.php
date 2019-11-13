<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worker_id')->unsigned()->index()->nullable();
            $table->foreign('worker_id')->references('id')->on('users');
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('users');
            $table->integer('table_id')->unsigned()->index()->nullable();
            $table->foreign('table_id')->references('id')->on('table');
            $table->boolean('takeaway');
            $table->enum('status', \App\Interfaces\StatusTypesInterface::TYPES)->default(\App\Interfaces\StatusTypesInterface::TYPE_ORDERED);
            $table->json('address')->nullable();
            $table->string('email')->nullable();
            $table->json('deliverer_location')->nullable();
            $table->text('comment')->nullable();
            $table->string('token');
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
        Schema::dropIfExists('order');
    }
}
