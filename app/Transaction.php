<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['amount'];

    public function account()
    {
        return $this->belongsTo('App\Account', 'id');
    }
}
