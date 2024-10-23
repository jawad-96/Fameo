<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use File, Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $appends = ['discountedPrice'];

    public function getPriceAttribute()
    {
        if(Auth::check() && Auth::guard('web')->check()) {
            $user = Auth::user();
            if($user->type == 'wholesaler') {
                $price_perc = $user->price_percentage;
                if($user->wholesaler_type=='1')
                    $price_perc = settingValue('wholesaler_percentage');

                if($price_perc>0)
                    return ($this->attributes['price'] + ($this->attributes['price'] * $price_perc/100));
            }

            if($user->type == 'dropshipper') {
                return ($this->attributes['cost'] + ($this->attributes['cost'] * Auth::user()->percentage_1/100));
            }
        }

        return $this->attributes['price'];
    }

    public function getDiscountedPriceAttribute()
    {
        $product = (object)$this->attributes;
        return getDiscountedPrice($product);
    }

    public function getSaleHtmlAttribute()
    {
        $html = '';
        $user = Auth::user();

        if(Auth::guard('web')->check()){
            if($user->type == 'retailer'){
                if($this->discount_type==1){
                    $html = '<span class="item-sale">'. $this->discount. '%</span>';
                }elseif($this->discount_type==2){
                    //$html = '<span class="item-sale">£'. number_format($this->discount,2) .'</span>';
                }
            }
        }else{
            if($this->discount_type==1){
                $html = '<span class="item-sale">'. $this->discount. '%</span>';
            }elseif($this->discount_type==2){
                //$html = '<span class="item-sale">£'. number_format($this->discount,2) .'</span>';
            }
        }

        return $html;

    }

    public function getDefaultImageUrlAttribute()
    {
        $image_name = '';
        $product_images = $this->product_images;
        $image = $product_images->where('default','1')->first();
        if($image){
            $image_name = $image->name;
        }else{
            $image = $product_images->first();
            if($image){
                $image_name = $image->name;
            }
        }
        return checkImage('products/'.$image_name);
    }

    public function getDefaultImageThumbAttribute()
    {
        $image_name = '';
        $product_images = $this->product_images;
        $image = $product_images->where('default','1')->first();
        if($image){
            $image_name = $image->name;
        }else{
            $image = $product_images->first();
            if($image){
                $image_name = $image->name;
            }
        }
        return checkImage('products/thumbs/'.$image_name);
    }

    public function getEncodedIdAttribute()
    {
        return Hashids::encode($this->id);
    }

    /**
     * belongs To relation User
     */

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function courier()
    {
    	return $this->belongsTo(Courier::class,'shipping_id','id');
    }

    /**
     * belongs To relation Store_products
     */

    public function store_products()
    {
    	return $this->hasMany(StoreProduct::class, 'product_id');
    }

    /**
     * belongs To relation Store_products
     */

    public function quantity()
    {
    	return $this->hasOne(StoreProduct::class, 'product_id');
    }
    
    /**
     * has Many relation Category_products
     */

    public function category_products()
    {
    	return $this->hasMany(CategoryProduct::class, 'product_id');
    }

    /**
     * has Many relation Product Images
     */
    public function product_images()
    {
    	return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * wishlist
     */
    public function isFavorite(){
        return $this->hasOne(UserWishList::class)->where('user_id', auth()->id());
    }

    /**
     * belongs To relation Product
     */

    public function product()
    {
    	return $this->belongsTo(Product::class,'product_id');
    }

    /**
     * has Many relation Products
     */
    public function products()
    {
    	return $this->hasMany(Product::class, 'product_id');
    }

    /**
     * has Many relation Product Attributes
     */
    public function product_attributes()
    {
    	return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    /**
     * has Many relation Product Variants
     */
    public function product_variants()
    {
    	return $this->hasMany(ProductVariant::class, 'product_id');
    }


    /**
     * has Many relation Product Variant
     */
    public function product_tags()
    {
    	return $this->hasMany(ProductTag::class, 'product_id');
    }

    /**
     * has Many relation Product View
     */
    public function product_views()
    {
        return $this->hasMany(ProductView::class, 'product_id');
    }

    /**
     * belongs To relation tax_rate
     */
    public function tax_rate()
    {
    	return $this->belongsTo(TaxRate::class, 'tax_rate_id');
    }

    /**
     * belongs To relation brands
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * belongs To relation shippings
     */
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    /**
     * Scopes
    */

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query and $category_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStores (Builder $query, $store_ids) {

        return $query->whereHas('store_products', function ($q) use ($store_ids) {
                $q->whereIn('store_id', $store_ids);
        });

    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query and $category_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShow (Builder $query) {

        if(Auth::check() && Auth::guard('web')->check()) {
            $user = Auth::user();
            return $query->where('view_' . $user->type, 1);
        }

        return $query->where('id', '>', 1);

    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query and $category_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategories (Builder $query, $category_id) {

        return $query->whereHas('category_products', function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
        });

    }

    /**
     * boot
     */
    protected static function boot ()
    {
    	parent::boot();
    	static::deleting(function($product) {

            $product->products()->delete();
            $product->store_products()->delete();
            $product->category_products()->delete();
            $product->product_attributes()->delete();
            $product->product_variants()->delete();
            $product->product_tags()->delete();

            foreach ($product->product_images()->get() as $image) {
                //remove image
                $destinationPath = 'uploads/products/';
                $thumbsDestinationPath = 'uploads/products/thumbs/';
                File::delete($destinationPath . $image->name);
                File::delete($thumbsDestinationPath . $image->name);

                $image->delete();
            }

    	});
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   protected $hidden = [
       'is_active', 'created_at', 'updated_at', 'deleted_at'
   ];


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'product_id', 'code', 'ean_number', 'sku', 'shipping_id', 'brand_id', 'supplier_id', 'tax_rate_id', 'type', 'barcode_symbology', 'cost', 'price', 'is_variants', 'discount_type', 'discount', 'detail', 'invoice_detail', 'is_active','is_default','is_main_price','is_main_tax','full_detail','tecnical_specs','new_arrivals','is_featured','is_hot'
        ,'meta_title','meta_description','slug', 'view_retailer', 'view_dropshipper', 'view_wholesaler'
        ];

}

