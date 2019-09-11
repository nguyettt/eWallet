<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('wallet_id')->unsigned();
            $table->integer('cat_id')->unsigned();
            $table->string('details');
            $table->double('amount');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wallet_id')->references('id')->on('wallets');
            $table->foreign('cat_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
