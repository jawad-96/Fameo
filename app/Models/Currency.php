<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Currency extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string 
     */
    protected $table = 'currencies';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';    
    
    
    
    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();
    
    	static::deleting(function($currencies) {
    		
    	});
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'symbol','direction'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];
    
	
}
