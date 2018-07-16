<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Config;

class Payment extends \Eloquent
{
    protected $table = 'payment_systems';
    protected $fillable = ['title', 'alias'];
    protected $hidden = ['id', 'config', 'payment_type', 'status'];
    public $timestamps = false;

//    public function operation()
//    {
//        return $this->belongsTo('App\Models\Operation');
//    }

    public static function getCommission($payment_alias, $sum)
    {
        $commission = Payment::where('alias', '=', $payment_alias)->first();
        $commission = json_decode($commission->config, true);
        return (($sum / 100) * $commission[$payment_alias.'_commission']);
    }

    public static function getProperty($payment_alias)
    {
        $model = Payment::where('paymant_alias', $payment_alias)->first();
        $config = json_decode($model->config);

        if ($payment_alias === 'perfect_money')
            return $config->perfect_money_code_phrase;
        elseif ($payment_alias === 'advanced_cash')
            return $config->advanced_cash;
        elseif ($payment_alias === 'payeer')
            return $config->payeer_secure_key;
    }
}
