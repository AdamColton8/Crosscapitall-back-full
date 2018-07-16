<?php

namespace App\Models;

class Wallet extends \Eloquent
{
    protected $table = 'wallets';
    protected $fillable = ['user_id', 'payment_alias', 'status', 'balance', 'image'];

    public  $timestamps = false;

    const WALLET_BONUS = 'bonus';
    const WALLET_REFERRAL = 'referral';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function operation()
    {
        return $this->hasMany('App\Models\Operation');
    }

    public function userPlan()
    {
        return $this->belongsToMany('App\Models\UserPlan');
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'alias', 'payment_alias');
    }

    public static function convertToCent($sum)
    { return $sum * 100;}

    public static function convertToDollar($sum)
    { return $sum / 100; }

    public static function accrualsReferralsBonus($user_id, $sum) //
    {
        $user = User::findOrFail($user_id);

        $users = explode('.', $user->path);

        $user_revers = array_reverse($users, false);

        foreach ($user_revers as $level => $user->id) {
           if ($level === 1)
           {
               $level = Config::getLevel('1');
               $balance = ($sum / 100) * $level->value;
               self::accrualsMoney($user->id, self::WALLET_REFERRAL, $balance);
           }
           else if ($level === 2)
           {

               $level = Config::getLevel('2');
               $balance = ($sum / 100) * $level->value;
               self::accrualsMoney($user->id, self::WALLET_REFERRAL, $balance);
           }
           else if($level === 3)
           {
               $level = Config::getLevel('3');
               $balance = ($sum / 100) * $level->value;
               self::accrualsMoney($user->id, self::WALLET_REFERRAL, $balance);
           }
        }
    }

    public static function getUserWallet($user_id, $payment_alias)
    {
        $wallet = Wallet::where('user_id', '=', $user_id)
            ->where('payment_alias', '=', $payment_alias)
            ->first();
        return $wallet;
    }

    public static function accrualsMoney($user_id, $payment_alias, $balance)
    {
        $wallet = self::getUserWallet($user_id, $payment_alias);
        if ($wallet)
        {
            $wallet->balance += $balance;
            $wallet->save();
        } else {
            return response()->json('wallet not found');
        }
    }

    public static function deductMoney($user_id, $payment_type, $sum) //the amount is convertible
    {
        $wallet = self::getUserWallet($user_id, $payment_type);
        $wallet->balance -= $sum;
        $wallet->save();
    }

    public static function createWallet($user_id, $payment_alias, $sum)
    {
        $wallet = new Wallet();
        $wallet->user_id = $user_id;
        $wallet->payment_alias = $payment_alias;
        $wallet->balance = $sum;
        $wallet->image = $payment_alias;
        $wallet->save();
    }

    public static function updateWalletBalance($wallet, $sum)
    {
        $wallet->balance += $sum;
        $wallet->save();
    }

    public static function refill($balance, $payment_alias)
    {
        //Getting post data
        $user_id = \Auth::id();
        $balance = self::convertToCent($balance);
        $wallet = Wallet::getUserWallet($user_id, $payment_alias);

        $commission = 0;
        //if user don't have wallet, create a new one or update exists
        If (null === $wallet)
            Wallet::createWallet($user_id, $payment_alias, $balance);
        else
            Wallet::updateWalletBalance($wallet, $balance);

        $status = Operation::STATUS_DONE; //
        $type_operation = Operation::TYPE_REFILL;
        Operation::newOperation($user_id, $wallet->id, $payment_alias, $balance, $commission, $type_operation, $status);
    }

    public static function getPercent($sum, $percent)
    {
        return ($sum * $percent / 100);
    }
}
