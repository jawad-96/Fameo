<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Shipping extends Model
{
    
    public function getImageUrlAttribute()
    {
        return checkImage('shippings/'.$this->image);
    }

    public function getImageThumbAttribute()
    {
        return checkImage('shippings/thumbs/'.$this->image);
    }

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
    protected $fillable = ['name', 'charges', 'image'];
    
}
