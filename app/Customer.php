<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = [
      'name', 'location', 'phone_number', 'lat', 'lng'
    ];

    public function orders() {
        return $this->hasMany('App\Order');
    }
}
