<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPlansFaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_plans_face', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('wallet_id')->unsigned();
            $table->foreign('wallet_id')->references('id')->on('wallets');
            $table->integer('plan_id')->unsigned();
            $table->foreign('plan_id')->references('id')->on('plans');
            $table->float('accumulation');
            $table->integer('initial_money');
            $table->integer('earned');
            $table->date('date');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_plans_face');
    }
}
