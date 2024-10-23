<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProductCombo extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_combos';

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
     * belongs To relation Product
     */

    public function product()
    {
    	return $this->belongsTo(Product::class,'product_id');
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
    protected $fillable = ['combo_product_id', 'product_id'];
    
	
}
