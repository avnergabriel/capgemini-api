<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'bank_account', 'password','hash'];

    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'id');
    }
}
