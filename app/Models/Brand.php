<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Brand extends Model
{
    
    public function getImageUrlAttribute()
    {
        return checkImage('brands/'.$this->image);
    }

    public function getImageThumbAttribute()
    {
        return checkImage('brands/thumbs/'.$this->image);
    }

    /**
     * has Many relation products
     */

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
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
    protected $fillable = ['name', 'image'];
    
}
