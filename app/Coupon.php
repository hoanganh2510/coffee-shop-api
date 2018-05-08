<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $fillable = [
      'description', 'code', 'percentage', 'expired_time'
    ];

    public function orders() {
        return $this->hasMany('App\Order');
    }
}
