<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends \Eloquent
{
    protected $table = 'configs';
    public $timestamps = false;

    //const for email settings
    const ES_EMAIL_TO_SEND = 'email_settings_email_to_send';
    const ES_NAME_TO_SEND = 'email_settings_name_to_send';
    const ES_IS_SMTP = 'email_settings_is_smtp';
    const ES_SMTP_LOGIN = 'email_settings_smtp_login';
    const ES_SMTP_PASSWORD = 'email_settings_smtp_password';
    const ES_SMTP_HOST = 'email_settings_smtp_host';

    //Referral program
    const RP_level_1 = 'referral_program_level_1';
    const RP_level_2 = 'referral_program_level_2';
    const RP_level_3 = 'referral_program_level_3';
    const RP_level = 'referral_program_level';

    //Payments system
    //Perfect money
    const PM_ACCOUNT_NUMBER = 'perfect_money_account_number';
    const PM_RECIPIENT_NAME = 'perfect_money_recipient_name';
    const PM_CODE_PHRASE = 'perfect_money_code_phrase';
    const PM_ACCOUNT_ID = 'perfect_money_account_id';
    const PM_ACCOUNT_PASSWORD = 'perfect_money_account_password';
    const PM_COMMISSION = 'perfect_money_commission';

    //Advanced cash
    const AC_EMAIL = 'advanced_cash_email';
    const AC_SCI_NAME = 'advanced_cash_sci_name';
    const AC_SCI_PASSWORD = 'advanced_cash_sci_password';
    const AC_API_NAME = 'advanced_cash_api_name';
    const AC_API_PASSWORD = 'advanced_cash_api_password';
    const AC_COMMISSION = 'advanced_cash_commission';

    //Payeer
    const P_ID = 'payeer_id';
    const P_SECURE_KEY = 'payeer_secure_key';
    const P_ACCOUNT_NUMBER = 'payeer_account_number';
    const P_API_ID = 'payeer_api_id';
    const P_API_KEY = 'payeer_api_key';
    const P_COMMISSION = 'payeer_commission';

    public static function getLevel($level)
    {
       $level = Config::where('key', '=', self::RP_level.'_'.$level)->first();
       return $level;
    }
}
