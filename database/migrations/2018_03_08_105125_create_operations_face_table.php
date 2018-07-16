<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationsFaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations_face', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('wallet_id')->unsigned();
            $table->foreign('wallet_id')->references('id')->on('wallets');
            $table->string('payment_alias', '50');
            $table->date('date');
            $table->integer('sum');
            $table->float('commission');
            $table->string('type');
            $table->string('status');
            $table->text('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations_face');
    }
}
