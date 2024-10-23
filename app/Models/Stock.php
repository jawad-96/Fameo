<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_history';

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
    	return $this->belongsTo(Store::class, 'store_id');
    }    
    
    /**
     * belongs To relation Product
     */
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
    
    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'product_id', 'store_id', 'user_id', 'quantity', 'stock_type', 'origin','note'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   protected $hidden = [ 'created_at','updated_at' ];
    
}
