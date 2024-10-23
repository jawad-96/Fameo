<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_products';
    
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    
    
    /**
     * belongs To relation Store
     */
    public function store()
    {
    	return $this->belongsTo(Store::class);
    }
    
    /**
     * belongs To relation Product
     */
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
    
    /**
     * has Many relation Product
     */
    public function products()
    {
    	return $this->hasMany(Product::class);
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['store_id' , 'product_id','quantity'];

}
