<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    //
    protected $fillable = [
        'status', 'customer_id', 'shop_id', 'coupon_id', 'shipping_fee'
    ];

    public function customer() {
        return $this->belongsTo('App\Customer');
    }

    public function shop() {
        return $this->belongsTo('App\Shop');
    }

    public function coupon() {
        return $this->belongsTo('App\Coupon');
    }

    public function oderItems() {
        return $this->hasMany('App\OrderItem');
    }
}
