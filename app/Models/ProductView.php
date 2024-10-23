<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProductView extends Model
{
    
    /**
     * belongs To relation product
     */
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'ip', 'agent'];
    
	
}
