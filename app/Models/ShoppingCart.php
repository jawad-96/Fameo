<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hashids;
use Illuminate\Database\Eloquent\Builder;

class ShoppingCart extends Model
{
	protected $appends = ['hashid'];

    protected $fillable = [
        'id',
        'user_id',
        'cart_details'
    ];

    public function getHashIdAttribute()
	{
	    return Hashids::encode($this->attributes['id']);
	}

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function courierAssignment(){
        return $this->hasOne(CouriersAssignment::class,'cart_id');
    }
}
