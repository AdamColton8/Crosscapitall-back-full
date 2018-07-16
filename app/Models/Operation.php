<?php

namespace App\Models;

class Operation extends \Eloquent
{
    protected $table = 'operations';
    protected $fillable = [
        'user_id',
        'wallet_id',
        'payment_alias',
        'date',
        'sum',
        'commission',
        'type',
        'status',
        'comment'
    ];

    public $timestamps = false;

    //types operation
    const TYPE_REFILL = 'refill';
    const TYPE_WITHDRAW = 'withdraw';

    const STATUS_DONE = 'done';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';

    const COMMENT_CANCELED = 'The request has been canceled by the user';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public  function wallet()
    {
        return $this->belongsTo('App\Models\Wallet');
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'alias', 'payment_alias');
    }

    public static function newOperation($user_id, $wallet_id, $payment_alias, $balance, $commission, $type, $status)
    {
        $operation = new Operation();
        $operation->user_id = $user_id;
        $operation->wallet_id = $wallet_id;
        $operation->payment_alias = $payment_alias;
        $operation->date = (new \DateTime())->format("Y:m:d h:m");
        $operation->sum = $balance;
        $operation->commission = $commission;
        $operation->type = $type;
        $operation->status = $status;
        $operation->comment = '';
        $operation->save();
    }
}