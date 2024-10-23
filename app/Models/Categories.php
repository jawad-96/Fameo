<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    
    public function getImageUrlAttribute()
    {
        return checkImage('categories/'.$this->image);
    }

    public function getImageThumbAttribute()
    {
        return checkImage('categories/thumbs/'.$this->image);
    }
    
    /**
     * has Many relation Subcategories
     */
    public function subcategories()
    {
    	return $this->hasMany(Categories::class, 'parent_id');
    }
   
    /**
     * belongs To relation Categories
     */
    public function category()
    {
    	return $this->belongsTo(Categories::class, 'parent_id');
    }
    
    /**
     * belongs To relation Store
     */
    public function store()
    {
    	return $this->belongsTo(Store::class, 'store_id');
    }
    
    /**
     * has Many relation Category_products
     */

    public function category_products()
    {
    	return $this->hasMany(CategoryProduct::class, 'category_id');
    }
    
    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();

    	static::deleting(function($category) {
            //$category->category_products()->delete();
    	});
    }
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'store_id', 'prefix','name', 'image', 'description', 'ordering', 'is_active'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   protected $hidden = [
       'image','is_active','created_at','updated_at'
   ];
   
    
}
