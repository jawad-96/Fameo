<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProductTag extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_tags';

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
    protected $fillable = ['product_id', 'name'];
    
	
}
