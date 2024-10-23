<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;
use App\Items;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Category_products;
use App\Models\ProductView;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Session, Image, File, Hashids, DataTables;
use Cart;

class ProductsController extends Controller
{
    public $resource = 'products';
    public $uploadPath = 'uploads/products';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(request $request)
    {
       //dd($request->all());
        $recent_products = Product::with(['category_products.category', 'store_products', 'isFavorite'])->where(['product_id' => 0,'new_arrivals'=>'1'])->limit(8)->get();
        $brands          = Brand::pluck('name', 'id');
        $products_check  = true;

        return view($this->resource.'/index', compact('recent_products', 'brands', 'products_check'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function getProducts(request $request)
    {
//        dd($request->all());
        $products = Product::with(['product_images','supplier', 'store_products', 'isFavorite', 'brand', 'tax_rate'])
                    ->where(['product_id' => 0])
                    ->show();
                    
//        categories filter
        if($request->category != 'all'){
            $category_id = explode('-', $request->category)[0];
            $category_id = (int)$category_id;
            $products->categories($category_id);
        }

//        brands filter
        $brands = $request->brand;

        if($brands){
            if(is_array($brands)){
                $brand_ids = [];
                foreach ($brands as $brand){
                    $brand_id = explode('-', $brand)[0];
                    $brand_ids[] = $brand_id;
                }
                if(count($brand_ids ) > 0){
                    $products->whereIn('brand_id', $brand_ids);
                }
            }else{
                $brand_id = explode('-', $brands)[0];
                $products->whereBrandId($brand_id);
            }
        }

        // order records
        if(!empty($request->order)){
            if($request->order == 'desc'){
                $products->orderBy('created_at', 'desc');
            }else if($request->order == 'asc'){
                $products->orderBy('created_at', 'asc');
            }else if($request->order == 'lowest'){
                $query = 'CAST(`price` AS DECIMAL(10,6)) ASC';
                $products->orderByRaw($query);
            }else if($request->order == 'highest'){
                $query = 'CAST(`price` AS DECIMAL(10,6)) DESC';
                $products->orderByRaw($query);
            }
        }

        if(!empty($request->search)){
            $products->where('name', 'like', '%'.$request->search.'%')
                     ->orWhere('code', 'like', '%'.$request->search.'%')
                     ->orWhere('sku', 'like', '%'.$request->search.'%')
                     ->orWhere('ean_number', 'like', '%'.$request->search.'%');
        }

        // min price
        if(!empty($request->min_price)){
            $products->where('price', '>=', (int)$request->min_price);
        }

        // max price
        if(!empty($request->max_price)){
            $products->where('price', '<=', (int)$request->max_price);
        }
        /*$products = $products->toSql();
        dd($products);*/

        $products = $products->paginate($request->records_per_page);

        return view('products.partial_records', compact('products'));
    }

    /**
     * Display a listing of the brands.
     *
     * @return \Illuminate\View\View
     */
    public function brands(request $request)
    {
        return view($this->resource.'/brands');
    }

    public function getBrands(request $request){
//        dd($request->brand);
        $brand = $request->brand;
        if($brand == 'all'){
            $brands          = Brand::withCount('products')->get();
        }else{
            $brands = Brand::withCount('products')->where('name', 'like', $brand.'%')->get();
        }
        return view('products.partial_brands', compact('brands'));
    }

    // utility method to build the categories tree
    function getCategoriesRecursive($all_categories, &$categories, $parent_id = 0, $depth = 0)
    {
        $cats = $all_categories->filter(function ($item) use ($parent_id) {
            return $item->parent_id == $parent_id;
        });

        foreach ($cats as $cat)
        {
                $categories[$cat->id] = array(
                  "id" => $cat->id,
                  "category_image" => $cat->image,
                  "category_name" => str_repeat('-', $depth) .' '. $cat->name,
                  "store_name" => @$cat->store->name,
                  "created_at" => $cat->created_at,
                );

            $this->getCategoriesRecursive($all_categories, $categories, $cat->id, $depth + 1);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
     public function show($id)
    {
        $requestid = $id;
        $id = decodeId($id);
        
        $product = Product::with(
            [
                'product_tags'=> function ($query) {
                    $query->orderBy('id', 'asc');
                },
                'store_products.store',
                'category_products.category.category',
                'product_images',
                'product_attributes.variant',
                'products.product_images',
                'products.product_variants',
                'brand'
            ])->show();

        if($id>0){
            $product = $product->findOrFail($id);
        }else{
            $product = $product->where('slug',$requestid )->firstOrFail();
        }

        if($product){
            $product_views['product_id'] = $product->id;
            $product_views['ip'] = \Request::getClientIp();

            $product_view = ProductView::firstOrNew($product_views);
            //$product_view->agent = \Request::header('User-Agent');
            $product_view->save();
        }

        // dd($product->toArray());
        $product_variants = [];
        if(@$product->is_variants=="1"){
            $images = $product->product_images->sortByDesc('default')->merge(@$product->products->pluck('product_images')->flatten());
            $product_variants = @$product->products->pluck('product_variants')->flatten()->groupBy('name');
        }else{
            $images = $product->product_images->sortByDesc('default');
        }

        //dd($product_variants->toArray());

        //dd($product->category_products->toArray());
        
        
        return view($this->resource.'/details', compact('product','images','product_variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $store_ids = Store::pluck('id');

        $categories = ['0' => 'Root Category'];

        return view($this->resource.'/create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required',
            'name' => 'required',
            'ordering' => 'required|integer|min:0'
        ]);

        $requestData = $request->all();

        $category = Categories::create($requestData);

        //save category image
        if($request->hasFile('category_image')){
            $image = $request->file('category_image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $category->id.'-'.str_random(10).'.'.$extension; // renameing image

            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($this->uploadPath).'/thumbs/'.$fileName);

            $image->move(public_path($this->uploadPath), $fileName); // uploading file to given path

            //update image record
            $category_image['image'] = $fileName;
            $category->update($category_image);
        }

        Session::flash('success', 'Category added!');
        return redirect($this->resource);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

        $id = decodeId($id);

        $category = Categories::with(['category'])->find($id);

        if($category->category){
            $categories = ['0' => 'Root Category', $category->category->id => $category->category->category_name];
        }else{
            $categories = ['0' => 'Root Category'];
        }

        return view($this->resource.'/edit', compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $id = decodeId($id);

        $this->validate($request, [
            'store_id' => 'required',
            'name' => 'required',
            'ordering' => 'required|integer|min:0'
        ]);

        $requestData = $request->all();

        $category = Categories::findOrFail($id);

        //save category image
        if($request->hasFile('category_image')){
            $image = $request->file('category_image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $category->id.'-'.str_random(10).'.'.$extension; // renameing image

            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($this->uploadPath).'/thumbs/'.$fileName);

            $image->move(public_path($this->uploadPath), $fileName); // uploading file to given path

            //remove old image
            File::delete(public_path($this->uploadPath) .'/'. $category->image);
            File::delete(public_path($this->uploadPath) .'/thumbs/'. $category->image);

            //update image record
            $requestData['image'] = $fileName;
        }

        $category->update($requestData);

        Session::flash('success', 'Category updated!');

        return redirect($this->resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $id = decodeId($id);

        $categories = Categories::find($id);

        if($categories){
            $categories->delete();
            $response['message'] = 'Categories deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Categories not exist against this id!';
            $status = $this->errorStatus;
        }

        return response()->json(['result'=>$response], $status);

    }

    /**
     * getStoreCategories function
     *
     * @param  int  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getStoreCategories($store_id, Request $request)
    {
        $categories = Categories::where('parent_id',0)->where('store_id',$store_id)->get();

        $categories = $categories->all();

        $status = $this->successStatus;

        return response()->json(['results' => $categories], $status);

    }


    public function getProductVariants(Request $request)
    {
      $variants = [];
      $variant_product = null;


      if($request->variant_id>0){
        $products = Product::with('product_variants.variant','product','store_products')->where('product_id',$request->product_id)->get();
        if($request->id==1){

          if($request->has('child_variant_id')){
            foreach($products as $product){
              if($product->product_variants->contains('name', $request->val)){
                $product_variants = $product->product_variants->where('name','!=', $request->val)->first();
                if($product_variants)
                  $variants[] = $product_variants->toArray();
              }
            }

          }else{
           
            foreach($products as $product){
                
              $v = $product->product_variants->where('name',$request->val);
              if($v->count()>0){
                $product->image_url = $product->default_image_url;
                $product->image_thumb = $product->default_image_thumb;
                $variant_product = $product;
              }
            }

          }

        }elseif($request->id==2){

          foreach($products as $product){
            $v = $product->product_variants->whereIn('name',[$request->parent_variant_id,$request->val]);
            if($v->count()>1){
              $product->image_url = $product->default_image_url;
              $product->image_thumb = $product->default_image_thumb;
              $variant_product = $product;
            }
          }

        }
      }else{
         
        $product = Product::with('product_variants.variant','product')->find($request->product_id);
        $product->image_url = $product->default_image_url;
        $product->image_thumb = $product->default_image_thumb;
        $variant_product = $product;
      }





    return response()->json(['variants' => $variants,'product'=>$variant_product], $this->successStatus);

  }

    public function addProductReview()
    {
        request()->merge(['created_at'=>Carbon::now(),'updated_at' => Carbon::now()]);
        $reviews = DB::table('product_reviews')->insert([
            request()->all()
        ]);

        return ['status' => true, 'message' => 'Review Added Successfully'];
    }

    public function getProductReview()
    {
        $reviews = DB::table('product_reviews')->where('product_id',\request()->product_id)->get();
        foreach ($reviews as $review){
            $review->dated = Carbon::parse($review->created_at)->format('F d, Y');
        }

        return ['status' => true,'data'=> $reviews];
    }

}
