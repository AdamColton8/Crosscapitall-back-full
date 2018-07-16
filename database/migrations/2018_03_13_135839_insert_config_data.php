<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertConfigData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('payment_systems')->insert([
                'alias' => 'perfect_money',
                'title' => 'Perfect Money',
                'status' => 'active',
                'config' => json_encode([
                    'perfect_money_account_number' => '123',
                    'perfect_money_recipient_name' => 'zfdb',
                    'perfect_money_code_phrase' => 'zrbd',
                    'perfect_money_account_id' => 'vszdbv',
                    'perfect_money_account_password' => 'asfav',
                    'perfect_money_commission' => '3',
                ]),
            ]
        );

        DB::table('payment_systems')->insert([
                'alias' => 'advanced_cash',
                'title' => 'Advanced cash',
                'status' => 'active',
                'config' => json_encode([
                    'advanced_cash_email' => '123',
                    'advanced_cash_sci_name' => 'zfdb',
                    'advanced_cash_sci_password' => 'zrbd',
                    'advanced_cash_api_name' => 'vszdbv',
                    'advanced_cash_api_password' => 'asfav',
                    'advanced_cash_commission' => '3',
                ]),
            ]
        );

        DB::table('payment_systems')->insert([
                'alias' => 'payeer',
                'title' => 'Payeer',
                'status' => 'active',
                'config' => json_encode([
                    'payeer_id' => '123',
                    'payeer_secure_key' => 'zfdb',
                    'payeer_account_number' => 'zrbd',
                    'payeer_api_id' => 'vszdbv',
                    'payeer_api_key' => 'asfav',
                    'payeer_commission' => '3',
                ]),
            ]
        );

        DB::table('payment_systems')->insert([
                'alias' => 'referrals',
                'title' => 'Referrals',
                'status' => 'active',
                'config' => '',
            ]
        );

        DB::table('payment_systems')->insert([
                'alias' => 'bonus',
                'title' => 'Bonus',
                'status' => 'active',
                'config' => '',
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
