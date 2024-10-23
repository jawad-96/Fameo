<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Variant extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'variants';

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
     * has Many relation product attributes
     */
    public function product_attributes()
    {
    	return $this->hasMany(Product_attribute::class, 'variant_id');
    }
    
    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();        
        
    	static::deleting(function($variant) {
    		
    	});
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    
	
}
