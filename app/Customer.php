<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customer';

    protected $fillable = [
      'name', 'email', 'phone_number'
    ];

    public function orders() {
        return $this->hasMany('App\Order');
    }
}
