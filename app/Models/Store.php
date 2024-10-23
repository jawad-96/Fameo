<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Store extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */ 
    protected $table = 'stores';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    
   
    /**
     * has Many relation Currency
     */
    public function currency()
    {
    	return $this->belongsTo(Currency::class, 'currency_id');
    }
    
    /**
     * has Many relation Categories
     */

    public function categories()
    {
    	return $this->hasMany(Categories::class, 'store_id');
    }
    
    /**
     * has Many relation Store_products
     */

    public function store_products()
    {
    	return $this->hasMany(StoreProduct::class, 'store_id');
    }
    
    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();
        
    	static::deleting(function($store) {
            $store->categories()->delete();
            $store->store_products()->delete();
    	});
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'currency_id', 'address', 'image'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at','deleted_at'];
	
}
