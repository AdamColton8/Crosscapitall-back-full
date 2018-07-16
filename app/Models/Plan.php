<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends \Eloquent
{
    protected $table = 'plans';
    protected $fillable = ['name', 'description', 'deposit_min', 'deposit_max', 'term', 'percent',];
    public $timestamps = false;

    public function userPlans()
    {
        return $this->belongsToMany('App\Models\UserPlan');
    }
}
