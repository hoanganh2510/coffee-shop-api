<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //
    protected $table = 'admin';

    protected $fillable = [
      'password_hash'
    ];

    protected $hidden = [
      'password_hash'
    ];
}
