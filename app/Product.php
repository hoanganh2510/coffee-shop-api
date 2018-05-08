<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'product';

    protected $fillable = [
      'name', 'image_url', 'category_id', 'description'
    ];

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function productDetails() {
        return $this->hasMany('App\ProductDetail');
    }

    public function orderItem() {
        return $this->hasOne('App\OrderItem');
    }
}
