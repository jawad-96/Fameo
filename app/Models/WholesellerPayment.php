<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesellerPayment extends Model
{
    
    protected $fillable = ['user_id', 'amount', 'date', 'payment_mode', 'note'];
    
    
     public function user()
    {
        return $this->belongsToMany(Models\User::class,'user_id');
    }
}
