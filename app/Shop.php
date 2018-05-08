<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop';
    //
    protected $fillable = [
        'address', 'lat', 'lng'
    ];

    public function orders() {
        return $this->hasMany('App\Order');
    }
}
