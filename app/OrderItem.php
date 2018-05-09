<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    protected $table = 'order_item';

    protected $fillable = [
      'product_id', 'quantity', 'price', 'size'
    ];

    public function order() {
        return $this->belongsTo('App\Order');
    }

    public function product() {
        return $this->belongsTo('App\Product');
    }
}
