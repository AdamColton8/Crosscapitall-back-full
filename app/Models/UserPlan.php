<?php

namespace App\Models;

use Auth;

class UserPlan extends \Eloquent
{
    protected $table = 'user_plans';
    protected $fillable = [
        'user_id',
        'wallet_id',
        'plan_id',
        'accumulation',
        'initial_money',
        'earned',
        'date',
        'status',
        'available',
        'date_and',
    ];

    public $timestamps = false;

    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSET = 'closed';
    const STATUS_COMPLETED = 'completed';

    public static function getDifference($date)
    {
        $now = time();
        $date = strtotime($date);

        $date_diff = $now - $date;

        return floor($date_diff/(60*60*24));
    }

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    public  function wallet()
    {
        return $this->hasOne('App\Models\Wallet');
    }

    public  function plan()
    {
        return $this->hasOne('App\Models\Plan', 'id', 'plan_id');
    }

    public static function activatePlan($payment_alias, $amount, $plan_id)
    {
        $wallet = Wallet::getUserWallet(Auth::id(), $payment_alias); //searching wallet of user
        $plan = Plan::where('id', $plan_id)->first(); //searching plan
        $initialMoney = Wallet::convertToCent($amount); //converting to cents

        //
        if ($plan->id === 1)
            $sum = (int) $amount + Wallet::getPercent($amount, $plan->percent);
        else $sum = (int)($amount * $plan->percent) / $plan->term;

        $dateEnd = new \DateTime();
        date_add($dateEnd, date_interval_create_from_date_string($plan->term.' days'));

        $plan = new UserPlan();
        $plan->user_id = Auth::id();
        $plan->wallet_id = $wallet->id;
        $plan->plan_id = $plan_id;
        $plan->initial_money = $amount;
        $plan->accumulation = $sum / $plan->term;
        $plan->earned = 0;
        $plan->date = (new \DateTime())->format("Y-m-d");
        $plan->status = UserPlan::STATUS_ACTIVE;
        $plan->available = $sum;
        $plan->date_end = $dateEnd->format("Y-m-d");
        $plan->save();
    }
}
