<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProductVariant extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_variants';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['updated_at'];
    
    /**
     * belongs To relation product attribute
     */
    public function product_attribute()
    {
    	return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
        
    public function variant()
    {
        return $this->hasOne(Variant::class, 'id','variant_id');
    }
    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();        
        
    	static::deleting(function($product_variant) {
    		//$product_variant->product_stock()->delete();
    	});
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'variant_id','name','sku','cost','price'];
    
	
}
