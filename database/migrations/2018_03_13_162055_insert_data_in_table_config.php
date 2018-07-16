<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInTableConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('configs')->insert([
                'key' => 'email_settings_email_to_send',
                'value' => '123',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'email_settings_name_to_send',
                'value' => '123',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'email_settings_is_smtp',
                'value' => '123',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'email_settings_smtp_login',
                'value' => '123',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'email_settings_smtp_password',
                'value' => '123',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'email_settings_smtp_host',
                'value' => '123',
            ]
        );

        DB::table('configs')->insert([
                'key' => 'referral_program_level_1',
                'value' => '7',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'referral_program_level_2',
                'value' => '2',
            ]
        );
        DB::table('configs')->insert([
                'key' => 'referral_program_level_3',
                'value' => '1',
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
