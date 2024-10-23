<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Courier extends Model
{

    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();
        
    	static::deleting(function($stores) {
    		
    	});
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'charges'];
    
}
