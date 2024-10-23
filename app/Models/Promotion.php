<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Promotion extends Model
{

	/**
     * belongs To Many relation proudcts
     */

	public function products()
	{
		return $this->belongsToMany(Product::class,'promotion_products');
	}
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'start_time', 'end_time'];
    
}
