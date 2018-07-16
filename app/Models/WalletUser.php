<?php

namespace App\Models;

class WalletUser extends \Eloquent
{
    protected $table = 'wallet_users';
    protected $fillable = ['user_id', 'name', 'payment_alias', 'wallet_key', 'wallet_name',  'active'];

    public  $timestamps = false;
    
    public function operation()
    {
        return $this->hasMany('App\Models\Operation');
    }
}
?>