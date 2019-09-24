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
            $table->integer('type');
            $table->string('details')->nullable();
            $table->double('amount');
            $table->integer('benefit_wallet')->unsigned()->nullable();
            $table->timestamps();
            $table->integer('delete_flag')->nullable();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->foreign('wallet_id')
                    ->references('id')
                    ->on('wallets')
                    ->onDelete('cascade');
            $table->foreign('cat_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade');
            $table->foreign('benefit_wallet')
                    ->references('id')
                    ->on('wallets')
                    ->onDelete('cascade');
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
