<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUserPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->date('date_end');
            $table->integer('available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn('date_end');
            $table->dropColumn('available');
        });
    }
}
