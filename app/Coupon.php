<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $table = 'coupon';

    protected $fillable = [
      'description', 'code', 'percentage', 'expired_time', 'status'
    ];

    public function orders() {
        return $this->hasMany('App\Order');
    }
}
