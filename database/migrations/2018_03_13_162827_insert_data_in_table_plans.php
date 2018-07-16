<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInTablePlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('plans')->insert([
                'name' => 'start',
                'description' => 'some text',
                'deposit_min' => '10',
                'deposit_max' => '300',
                'term' => '45',
                'percent' => '20',
            ]
        );
        DB::table('plans')->insert([
                'name' => 'medium',
                'description' => 'some text',
                'deposit_min' => '10',
                'deposit_max' => '300',
                'term' => '45',
                'percent' => '20',
            ]
        );
        DB::table('plans')->insert([
                'name' => 'pro',
                'description' => 'some text',
                'deposit_min' => '1000',
                'deposit_max' => '2000',
                'term' => '45',
                'percent' => '20',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
