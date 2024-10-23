<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\CategoryProduct;
use App\Models\ProductAttribute;
use App\Models\ProductTag;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Brand;
use App\Models\Shipping;
use Session, Image, File, Hashids, DataTables;

class ProductsController extends Controller
{
    public $resource = 'admin/products';

    public function __construct()
   {
        $this->middleware('permission:view products', ['only' => ['index']]);
        $this->middleware('permission:add products', ['only' => ['create','store']]);
        $this->middleware('permission:edit products', ['only' => ['edit','update']]);
        $this->middleware('permission:delete products', ['only' => ['destroy']]);
        $this->middleware('permission:view product stocks', ['only' => ['productStocks','getProductStocks']]);
        $this->middleware('permission:view_comments', ['only' => ['productFeedBack']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            $products = Product::with([
              'products' => function ($query) {
                  $query->orderBy('id', 'asc');
              },
              'products.product_images',
              'products.store_products',
              'store_products.store',
              'category_products.category',
              'product_images',
              'quantity',
            ])->where('product_id',0)->orderBy('updated_at','desc');

            return DataTables::of($products)
                ->editColumn('price', function ($stock) {
                    $price = $stock->price;

                    return $price;
                })
                ->addColumn('is_variants', function ($product) {
                    if($product->is_variants)
                        return '<span class="details-control"></span>';
                    else
                        return '';
                })
                ->addColumn('sku', function ($product) {
                    if(($product->is_variants) || ($product->type==3))
                        return '-';
                    else
                        return $product->sku;
                })
                ->addColumn('product_image', function ($product) {
                    if($product->is_variants)
                        return '<i class="fa fa-sitemap fa-2x"></i>';
                    else
                        return '<img width="35" src="'. getProductDefaultImage($product->id) .'" />';
                })
                ->addColumn('supplier', function ($product) {
                    return @$product->supplier->name;
                })
                ->addColumn('action', function ($product) {
                    $action = '<a href="products/make-copy/'. Hashids::encode($product->id).'" class="text-success" data-toggle="tooltip" title="Make a Copy"><i class="fa fa-lg fa-copy"></i></a>';
                    if(Auth::user()->can('edit products'))
                        $action .= '<a href="products/'. Hashids::encode($product->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Product"><i class="fa fa-lg fa-edit"></i></a>';
                    if(Auth::user()->can('view product stocks'))
                        $action .= '<a href="product-stocks/'. $product->id.'" class="text-success" data-toggle="tooltip" title="Stock History"><i class="fa fa-lg fa-bar-chart-o"></i></a>';
                    if(Auth::user()->can('delete products'))
                        $action .= '<a href="products/'. Hashids::encode($product->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Product" id="'.Hashids::encode($product->id).'"><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;
                })
                ->rawColumns(['price','is_variants','name','product_image', 'supplier', 'store', 'quantity', 'action'])
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);
        }

        return view($this->resource.'/index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function productStocks($product_id)
    {
        //$product_id = Hashids::decode($product_id)[0];

        $product = Product::find($product_id);

        return view('admin.products.stock_history', compact('product'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getProductStocks($product_id)
    {

        $store_ids = Store::pluck('id');

        $stocks = Stock::with(['store','product'])->where('product_id',$product_id)->whereIn('store_id',$store_ids)->orderBy('id','desc')->get();


        return Datatables::of($stocks)
            ->addColumn('created_at', function ($stock) {
                return date('d-m-Y h:i a', strtotime($stock->created_at));
            })
            ->addColumn('store_name', function ($stock) {
                return $stock->store->name;
            })
            ->addColumn('product_name', function ($stock) {
                return $stock->product->name;
            })
            ->addColumn('stock_type', function ($stock) {
                if($stock->stock_type==1)
                    return "IN";
                elseif($stock->stock_type==2)
                    return "OUT";
            })
            ->addColumn('origin', function ($stock) {
                switch ($stock->origin) {
                    case 1:
                        return "Add Product";
                        break;
                    case 2:
                        return "Update Product";
                        break;
                    case 3:
                        return "Sale";
                        break;
                    case 4:
                        return "Sale Return";
                        break;
                    case 5:
                        return "Adjustment";
                        break;
                    default:
                        return "Add Product";
                }
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['store_name', 'product_name'])
            ->make(true);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $images = [];
        $brands = Brand::pluck('name','id')->prepend('Select Brand','');
        $shippings = Shipping::pluck('name','id');
        $shippings = Courier::pluck('name','id');
        $categories = Categories::whereNotNull('prefix')->pluck('prefix','prefix')->prepend('Select Prefix','');

        return view($this->resource.'/create', compact('images','brands','shippings','categories'));
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
        $rules['code'] = 'required|unique:products';
        $rules['ean_number'] = 'required';
        $rules['name'] = 'required';
        $rules['tax_rate_id'] = 'required';
        $rules['barcode_symbology'] = 'required';
        $rules['cost'] = 'required|numeric';
        $rules['price'] = 'required|numeric';
        $rules['wholesaler_price'] = 'numeric';
        $rules['shipping_id'] = 'required|numeric';

        if(empty($request->is_variants)){
           $rules['sku'] = 'required|unique:products';
        }


        $this->validate($request, $rules);

        $requestData = $request->all();

        $requestData['sku'] = empty($requestData['sku']) ? 0 : $requestData['sku'];
        $requestData['cost'] = empty($requestData['cost']) ? 0 : $requestData['cost'];
        $requestData['price'] = empty($requestData['price']) ? 0 : $requestData['price'];
        $requestData['wholesaler_price'] = empty($requestData['wholesaler_price']) ? 0 : $requestData['wholesaler_price'];
        $requestData['discount_type'] = empty($requestData['discount_type']) ? '0' : $requestData['discount_type'];
        $requestData['discount'] = empty($requestData['discount']) ? 0 : $requestData['discount'];
        $requestData['supplier_id'] = empty($requestData['supplier_id']) ? 0 : $requestData['supplier_id'];
        $requestData['brand_id'] = empty($requestData['brand_id']) ? 0 : $requestData['brand_id'];
        $requestData['meta_title'] = $requestData['meta_title'];
        $requestData['slug'] = $requestData['slug'];
        $requestData['meta_description'] = $requestData['meta_description'];
        
        $requestData['view_retailer'] = empty($requestData['view_retailer']) ? 0 : $requestData['view_retailer'];
        $requestData['view_dropshipper'] = empty($requestData['view_dropshipper']) ? 0 : $requestData['view_dropshipper'];
        $requestData['view_wholesaler'] = empty($requestData['view_wholesaler']) ? 0 : $requestData['view_wholesaler'];

        $product = Product::create($requestData);

        if($product){

            if(!empty($request->tags)){
                $tags = explode(',', $request->tags);
                foreach($tags as $tag){
                    $tag_data['product_id'] = $product->id;
                    $tag_data['name'] = $tag;
                    ProductTag::create($tag_data);
                }
            }

            if(isset($requestData['image_ids'])){
                foreach($requestData['image_ids'] as $image_id){
                      $product_images = ProductImage::find($image_id);
                      if($product_images){
                        $product_images->product_id = $product->id;
                        $product_images->update();
                      }

                }
              }

            Session::flash('success', 'Product successfully added!');
        }else{
            Session::flash('error', 'Product not successfully added!');
        }

        return redirect($this->resource.'/'. Hashids::encode($product->id) .'/edit?tab=2');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category_id)
    {
        $subcategory_id = '';
        return view('admin.items.index', compact('category_id','subcategory_id'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
     public function edit($id)
    {
        $id = decodeId($id);

        $product = Product::with(
        [
            'product_tags'=> function ($query) {
                $query->orderBy('id', 'asc');
            },
            'store_products.store',
            'category_products.category',
            'product_images'
        ])->findOrFail($id);

        //dd($product->toArray());

        $images = $product->product_images->sortBy('id');
        $brands = Brand::pluck('name','id')->prepend('Select Brand','');
        $shippings = Shipping::pluck('name','id');
        $shippings = Courier::pluck('name','id');
        $categories = Categories::whereNotNull('prefix')->pluck('prefix','prefix')->prepend('Select Prefix','');

        return view($this->resource.'/edit', compact('product','images','brands','shippings','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
    public function update($id, Request $request)
    {
        $id = decodeId($id);

        $rules['code'] = 'required|unique:products,code,'.$id;
        $rules['ean_number'] = 'required';
        $rules['name'] = 'required';
        $rules['tax_rate_id'] = 'required';
        $rules['barcode_symbology'] = 'required';
        $rules['product_images'] = 'required|numeric';
        $rules['shipping_id'] = 'required|numeric';

        if(empty($request->is_variants)){
           $rules['sku'] = 'required|unique:products,sku,'.$id;
        }

        $rules['cost'] = 'required|numeric';
        $rules['price'] = 'required|numeric';
        $rules['wholesaler_price'] = 'numeric';

        $this->validate($request, $rules);

        $product = Product::findOrFail($id);

        $requestData = $request->all();

        $requestData['sku'] = empty($requestData['sku']) ? 0 : $requestData['sku'];
        $requestData['cost'] = empty($requestData['cost']) ? 0 : $requestData['cost'];
        $requestData['price'] = empty($requestData['price']) ? 0 : $requestData['price'];
        $requestData['wholesaler_price'] = empty($requestData['wholesaler_price']) ? 0 : $requestData['wholesaler_price'];
        $requestData['discount_type'] = empty($requestData['discount_type']) ? '0' : $requestData['discount_type'];
        $requestData['discount'] = empty($requestData['discount']) ? 0 : $requestData['discount'];
        $requestData['supplier_id'] = empty($requestData['supplier_id']) ? 0 : $requestData['supplier_id'];
        $requestData['brand_id'] = empty($requestData['brand_id']) ? 0 : $requestData['brand_id'];
        $requestData['meta_title'] = $requestData['meta_title'];
        $requestData['slug'] = $requestData['slug'];
        $requestData['meta_description'] = $requestData['meta_description'];

        $requestData['view_retailer'] = empty($requestData['view_retailer']) ? 0 : $requestData['view_retailer'];
        $requestData['view_dropshipper'] = empty($requestData['view_dropshipper']) ? 0 : $requestData['view_dropshipper'];
        $requestData['view_wholesaler'] = empty($requestData['view_wholesaler']) ? 0 : $requestData['view_wholesaler'];

        $product->update($requestData);

        // remove product tags
        ProductTag::where('product_id',$product->id)->delete();
        if(!empty($request->tags)){
            $tags = explode(',', $request->tags);
            foreach($tags as $tag){
                $tag_data['product_id'] = $product->id;
                $tag_data['name'] = $tag;
                ProductTag::create($tag_data);
            }
        }

        if(isset($requestData['image_ids'])){
            foreach($requestData['image_ids'] as $image_id){
                  $product_images = ProductImage::find($image_id);
                  $product_images->product_id = $product->id;
                  $product_images->update();
            }
          }


        Session::flash('success', 'Product successfully updated!');

        return redirect('admin/products/'. Hashids::encode($product->id) .'/edit?tab=1');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
    public function updateStore($id, Request $request)
    {
        $id = Hashids::decode($id)[0];

        $this->validate($request, [
            'store_category_ids' => 'required',
        ]);

        $product = Product::findOrFail($id);

        $requestData = $request->all();

        $product->update($requestData);

        // remove store products and category products and product stock and product tags
        CategoryProduct::where('product_id',$product->id)->delete();

        $store_category_ids = explode(',', $request->store_category_ids);

        $old_store_ids = StoreProduct::select('store_id','quantity')->where('product_id',$product->id)->get();
        $new_store_ids = [];

        foreach($store_category_ids as $store_category_id){
            $store_category = explode('-', $store_category_id);

            if($store_category[0] == "store"){

                $new_quantity = empty($requestData['store_quantity_'.$store_category_id]) ? 0 : $requestData['store_quantity_'.$store_category_id];
                $store_id = $store_category[1];

                // Check store product exist or not
                $store_product_data = StoreProduct::where('product_id',$product->id)->where('store_id',$store_id)->first();

                if($store_product_data){

                    if($new_quantity > $store_product_data->quantity){
                       // stock add
                       $add_quantity = $new_quantity - $store_product_data->quantity;
                       updateProductStockByData($product->id, $store_id, $add_quantity, 1, 2, 0, 0, 'Edit Product');
                    }elseif($new_quantity < $store_product_data->quantity){
                       // stock remove
                       $remove_quantity = $store_product_data->quantity - $new_quantity;
                       updateProductStockByData($product->id, $store_id, $remove_quantity, 2, 2, 0, 0, 'Edit Product');
                    }
                }else{
                    // save new store product
                    $store_product['product_id'] = $product->id;
                    $store_product['store_id'] = $store_id;
                    $store_product['quantity'] = $new_quantity;

                    $store_products = StoreProduct::create($store_product);

                    updateProductStockByData($product->id, $store_id, $new_quantity, 1, 1, 0, 0, 'Add Product');
                }

                $new_store_product['store_id'] = $store_id;
                $new_store_product['quantity'] = empty($requestData['store_quantity_'.$store_category_id]) ? 0 : $requestData['store_quantity_'.$store_category_id];

                array_push($new_store_ids, $new_store_product);

            }

            if($store_category[0] == "category" || $store_category[0] == "subcategory"){
               $category_id =  $store_category[1];

               if($store_category[0] == "category"){
                   // save store product
                   $store_id = Categories::find($category_id)->store_id;

                   $store_product['product_id'] = $product->id;
                   $store_product['store_id'] = $store_id;

                   $store_products = StoreProduct::firstOrNew($store_product);
                   $store_products->save();
               }

               if($store_category[0] == "subcategory"){

                   $parent_id = Categories::find($category_id)->parent_id;

                   $store_id = Categories::find($parent_id)->store_id;

                   // save store product
                   $store_product['product_id'] = $product->id;
                   $store_product['store_id'] = $store_id;

                   $store_products = StoreProduct::firstOrNew($store_product);
                   $store_products->save();

                    // save category products
                    $category_product['product_id'] = $product->id;
                    $category_product['category_id'] = $parent_id;

                    $category_products = CategoryProduct::firstOrNew($category_product);
                    $category_products->save();
               }

                // save category products
                $category_product['product_id'] = $product->id;
                $category_product['category_id'] = $category_id;

                $category_products = CategoryProduct::firstOrNew($category_product);
                $category_products->save();
            }
        }

        foreach($old_store_ids as $old_store_id){
            if(find_key_value($new_store_ids,'store_id',$old_store_id['store_id'])){

            }else{
                // Check store product exist or not
                $store_product_data = StoreProduct::where('product_id',$product->id)->where('store_id',$old_store_id['store_id'])->first();

                if($store_product_data){
                    // stock remove
                    $remove_quantity = $store_product_data->quantity;
                    updateProductStockByData($product->id, $old_store_id['store_id'], $remove_quantity, 2, 2, 0, 0, 'Edit Product');
                }
            }
        }

        Session::flash('success', 'Product successfully updated!');

        return redirect('admin/products/'. Hashids::encode($product->id) .'/edit?tab=2');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
    public function destroy($id)
    {
        if(\Request::ajax())
        {
            $id = Hashids::decode($id)[0];
        }

        $product = Product::find($id);

        if($product){
            $product->delete();
            $response['message'] = 'Product deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Product not exist against this id!';
            $status = $this->notFoundStatus;
        }

        return response()->json(['result'=>$response], $status);
    }

    /**
     * getAllStoreCategories function
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllStoreCategories($product_id = 0){

        $product_type = 0;
        if($product_id>0)
            $product_type = Product::find($product_id)->type;

        $store_categories = Store::with(
                [
                    'categories' => function ($query) {
                        $query->where('parent_id', 0);
                    },
                    'store_products'
                ]
            )->get();

        $store_categories->map(function ($store) use ($product_id, $product_type){

            $store['data_id'] = 'store-'. $store->id;
            $store['text'] = $store->name .' <b>(Store)</b>';
            $store['imageCssClass'] = 'glyphicon glyphicon-home';

            if($product_id>0){
                $store['checked'] = $store->store_products->contains('product_id', $product_id);
            }else{
                if($store->id == companySettingValue('store_id'))
                    $store['checked'] = true;
            }

            if($product_type != 3){
                if($store->categories->count() > 0){
                    $categories = $store->categories->map(function ($category) use ($product_id) {

                        $category['data_id'] = 'category-'. $category->id;
                        $category['text'] = $category->name;
                        $category['imageCssClass'] = 'glyphicon glyphicon-list';
                        $category['checked'] = $category->category_products->contains('product_id', $product_id);

                        if($category->subcategories->count() > 0){
                            $subcategories = $category->subcategories->map(function ($subcategory) use ($product_id) {
                                $subcategory['data_id'] = 'subcategory-'. $subcategory->id;
                                $subcategory['text'] = $subcategory->name;
                                $subcategory['imageCssClass'] = 'glyphicon glyphicon-list';
                                $subcategory['checked'] = $subcategory->category_products->contains('product_id', $product_id);

                                return $subcategory;
                            });

                            $category['children'] =  $subcategories;
                        }

                        return $category;
                    });

                    $store['children'] =  $categories;
                }
            }

            unset($store['company_id']);
            unset($store['currency_id']);
            unset($store['image']);
            unset($store['address']);
            unset($store['created_at']);
            unset($store['updated_at']);
            return $store;
         });

        // dd($store_categories->toJson());

         return response()->json($store_categories);
    }

    /**
     * getStoreCategories function
     *
     * @param  int  $store_id
     *
     * @return \Illuminate\Http\Response
     */

    public function getStoreCategories($store_id){


        $all_categories = Categories::where('store_id',$store_id)->get();

        $categories = [];
        if($all_categories){
            $status = true;
           // $categories[0] = ['id'=>'','text'=>'Select Categories'];
            $this->getCategoriesRecursive($all_categories, $categories);
        }else{
            $status = false;
            //$categories[0] = ['id'=>'','text'=>'Please select other store'];
        }


        return response()->json(['status' => $status,'categories' => $categories]);
    }

    // utility method to build the categories tree
    function getCategoriesRecursive($all_categories, &$categories, $parent_id = 0, $depth = 0)
    {
        $cats = $all_categories->filter(function ($item) use ($parent_id) {
            return $item->parent_id == $parent_id;
        });

        foreach ($cats as $key => $cat)
        {
                $categories[$key] = array(
                  "id" => $cat->id,
                  "text" => str_repeat('-', $depth) .' '. $cat->name,
                );

            $this->getCategoriesRecursive($all_categories, $categories, $cat->id, $depth + 1);
        }
    }

    /**
     * getProductsMap function
     *
     * @param  int  $products
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductsMap($products)
    {

        $products->map(function ($product) {

            if($product->is_modifier == 1){
                $product->product_modifiers->map(function ($modifier) {
                    $modifier->name = $modifier->modifier->name;
                    $modifier->price = $modifier->modifier->price;

                    unset($modifier->product_id);
                    unset($modifier->created_at);
                    unset($modifier->modifier);
                    return $modifier;
                });
            }

            return $product;

        });

          return $products->all();
    }

    /**
    * Method : POST
    *
    * @param \Illuminate\Http\Request $request
    *
    *  @return image id and name
    */
    public function storeImage(Request $request)
    {

      $validator = \Validator::make($request->all(),[
        //"file"      => "dimensions:min_width=1000,min_height=1000,max_width=1100,max_height=1100"
     ]);

     if($validator->fails()){
        return response()->json(['message' => $validator->messages()->first()],422);
     }

    	if($request->file('file') && $request->file('file')->isValid()){
          $destinationPath = public_path('uploads/products'); // upload path
          $image = $request->file('file'); // file
          $extension = $image->getClientOriginalExtension(); // getting image extension
          $fileName = str_random(12).'.'.$extension; // renameing image

          $img = Image::make($image->getRealPath());
          //$watermark = Image::make(public_path('/images/watermark.jpg'));
          //$img->insert(public_path('/images/watermark.jpg'), 'bottom-right', 10, 10);
          $img->resize(100, 100, function ($constraint) {
              $constraint->aspectRatio();
          })->save($destinationPath.'/thumbs/'.$fileName);

          $image->move($destinationPath, $fileName); // uploading file to given path

          $img1 = Image::make($destinationPath.'/'.$fileName);
          //$img1->insert(public_path('images/watermark.png'), 'bottom-left',20,20);
          //$img1->save(public_path($destinationPath.'/'.$fileName));

          //insert image record
          $product_image['product_id'] = 0;
          $product_image['name'] = $fileName;
          $product_image = ProductImage::create($product_image);

      		if($product_image){
            return response()->json(['id'=>$product_image->id,'name'=>$fileName,'image_url'=>url('uploads/products/'.$fileName)]);
      		}else{
            return response()->json(['message' => 'Error while saving image'],422);
      		}
    	}else{
    		return response()->json(['message' => 'Invalid image'],422);
    	}
    }

    /**
    * Method : DELETE
    *
    * @return delete images
    */
    public function deleteImage($id)
    {
    	$image = ProductImage::findOrFail($id);
        if ($image) {

//            $file = public_path() . '/uploads/products/'.$image->name;
//            $thumbFile = public_path() . '/uploads/products/thumbs/'.$image->name;
//            if(is_file($file)){
//                @unlink($file);
//                @unlink($thumbFile);
//            }

            $image->delete();
        }

        return response()->json(['success' => 1]);
    }

    /**
    * Method : POST
    *
    * @param \Illuminate\Http\Request $request
    *
    * @return message
    */
    public function setDefaultImage(Request $request)
    {

        ProductImage::whereIn('id',$request->image_ids)->update(['default'=>'0']);

        if($request->checked == 1){
           $product_image = ProductImage::find($request->image_id);
           $product_image->default = '1';
           $product_image->update();
        }

    }


    /**
     * getProductAttributes function
     *
     * @param  int  $product_id
     *
     * @return \Illuminate\Http\Response
     */

    public function getProductAttributes($product_id){

        $product_attributes = ProductAttribute::with(['variant'])->where('product_id',$product_id)->orderBy('id','asc')->get();

        return response()->json(['product_attributes' => $product_attributes]);
    }

    /**
    * Method : POST
    *
    * @param \Illuminate\Http\Request $request
    *
    *  @return response
    */
    public function createProductAttribute(Request $request)
    {
       if($request->attribute_id == 0){
            $product_variant['product_id'] =  $request->product_id;
            $product_variant['variant_id'] =  $request->attribute_id;

            $product_variants = ProductAttribute::firstOrNew($product_variant);
            $product_variants->save();
       }elseif($request->attribute_id > 0){
            $product_variant['product_id'] =  $request->product_id;
            $product_variant['variant_id'] =  $request->attribute_id;

            $product_variants = ProductAttribute::updateOrCreate($product_variant);
            $product_variants->save();
       }


    }

    /**
    * Method : DELETE
    *
    * @return delete product attribute
    */
    public function removeProductAttribute($id)
    {
    	$attribute = ProductAttribute::findOrFail($id);
        if ($attribute) {
            $attribute->delete();
        }

        return response()->json(['success' => 1]);
    }

    /**
     * getProductVariants function
     *
     * @param  int  $product_id
     *
     * @return \Illuminate\Http\Response
     */

    public function getProductVariants($product_id){

        $product_variants = Product::where('product_id',$product_id)->orderBy('id','asc')->get();

        $product_variants->map(function ($variant) {

            $variant['encoded_id'] = Hashids::encode($variant['id']);

            return $variant;
        });

        return response()->json(['product_variants' => $product_variants]);
    }

    /**
    * Method : POST
    *
    * @param \Illuminate\Http\Request $request
    *
    *  @return response
    */
    public function createProductVariant(Request $request)
    {
        $product = Product::with('store_products')->find($request->product_id);
        $post_data = array();
        parse_str($request->attribute_data, $post_data);

        $variant_name = [];
        for($i=1; $i<=(int)$post_data['total_attributes']; $i++){
            $variant_name[] = $post_data['attribute-'.$i];
        }

        $requestData['product_id'] = $product->id;
        $requestData['name'] = $product->name .' - '. implode(', ', $variant_name);
        $requestData['cost'] = empty($post_data['cost']) ? 0 : $post_data['cost'];
        $requestData['price'] = empty($post_data['price']) ? 0 : $post_data['price'];
        $requestData['code'] = 0;
        $requestData['sku'] = 0;
        $requestData['discount_type'] = '0';
        $requestData['discount'] = 0;
        $requestData['supplier_id'] = 0;
        $requestData['tax_rate_id'] = settingValue('tax_id');
        $requestData['is_main_price'] = $post_data['is_main_price']??0;

        $variant_product = Product::create($requestData);

        if($variant_product){
            for($i=1; $i<=(int)$post_data['total_attributes']; $i++){
                $variant_data['variant_id'] = $post_data['attribute-id-'.$i];
                $variant_data['product_id'] = $variant_product->id;

                $product_variant = ProductVariant::updateOrCreate($variant_data);
                $product_variant->name= $post_data['attribute-'.$i];
                $product_variant->save();
            }

            //set first record as default
            $this->setFirstProductAsDefault($variant_product->id);

            $product->store_products->map(function($store_product) use ($variant_product) {

                $variant_store_product = [];
                $variant_store_product['store_id'] =  $store_product->store_id;
                $variant_store_product['product_id'] =  $variant_product->id;
                $variant_store_product['quantity'] =  0;

                StoreProduct::create($variant_store_product);

                return $store_product;
            });
        }


    }

    /**
    * Method : POST
    *
    * @param \Illuminate\Http\Request $request
    *
    *  @return response
    */
    public function setProductAsDefault(Request $request)
    {
        $product = Product::find($request->product_id);

        Product::where('product_id',$product->product_id)->update(['is_default' => 0]);

        $product->is_default = $request->value;
        $product->save();

        //set first record as default
        $this->setFirstProductAsDefault($product->id);
    }

    /**
    *
    * @param $product_id
    *
    */
    private function setFirstProductAsDefault($product_id)
    {
        $product = Product::find($product_id);

        $total_default_products = Product::where('product_id',$product->product_id)->where('is_default' , 1)->count();

        if($total_default_products==0){
            $first_product = Product::where('product_id',$product->product_id)->orderBy('id','asc')->first();
            $first_product->is_default = 1;
            $first_product->save();
        }

    }

    /**
    * Method : DELETE
    *
    * @return delete images
    */
    public function removeVariant($id)
    {
    	$variant = Product_attribute::findOrFail($id);
        if ($variant) {
            $variant->delete();
        }

        return response()->json(['success' => 1]);
    }


    /**
     * getProductModifiers function
     *
     * @param  int  $product_id
     *
     * @return \Illuminate\Http\Response
     */

    public function getProductModifiers($product_id){

        $product_store_ids = Store_products::where('product_id',$product_id)->pluck('store_id');

        $modifier_ids = Product::where('type',3)->pluck('id');

        $modifer_store_ids = Store_products::whereIn('product_id',$modifier_ids)->pluck('store_id');

        $merged = $product_store_ids->merge($modifer_store_ids);

        $store_unique_ids = $merged->unique();

        $product_ids = Store_products::whereIn('store_id',$store_unique_ids)->pluck('product_id');

        $product_modifiers = Product::where('type',3)->whereIn('id',$product_ids)->get();

        $product_modifiers->map(function($product_modifier) use($product_id){

            $product_modifier['is_checked'] = false;

            $modifier['product_id'] = $product_id;
            $modifier['modifier_id'] = $product_modifier->id;

            $is_modifier = Product_modifier::where($modifier)->first();
            if($is_modifier)
                $product_modifier['is_checked'] = true;

            return $product_modifier;
        });

        return response()->json(['product_modifiers' => $product_modifiers]);
    }

    /**
    * Method : POST
    *
    * @param \Illuminate\Http\Request $request
    *
    *  @return response
    */
    public function setProductModifier(Request $request)
    {
        $product_modifier['product_id'] = $request->product_id;
        $product_modifier['modifier_id'] = $request->modifier_id;

        if($request->value==1){
            $product_modifiers = Product_modifier::firstOrNew($product_modifier);
            $product_modifiers->save();
        }else{
            Product_modifier::where($product_modifier)->delete();
        }

    }
        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
     public function editVariant($id)
    {
        //if($id>0)
          //  return redirect('admin/products/edit/'. Hashids::encode($id));

        //$id = decodeId($id);

        $product = Product::with(
                [
                    'product_tags' => function ($query) {
                        $query->orderBy('id', 'asc');
                    },
                    'product_variants.product_attribute.variant',
                    'product.store_products.store',
                    'product.product_attributes.variant',
                    'category_products.category',
                    'product_images'
                ]
            )->findOrFail($id);

        if($product->product_id == 0)
            return redirect('admin/products/'. Hashids::encode($product->id) .'/edit');


        $categories = Categories::whereNotNull('prefix')->pluck('prefix','prefix')->prepend('Select Prefix','');
        //dd($product->toArray());
        return view('admin.products.edit-variant', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
    public function updateVariantProduct($id, Request $request)
    {

        $id = decodeId($id);

        $requestData = $request->all();

        $rules['code'] = 'required|unique:products,code,'.$id;
        $rules['name'] = 'required|max:100';
        $rules['sku'] = 'required|unique:products,sku,'.$id;
        $rules['tax_rate_id'] = 'required';

        if(empty($request->is_main_price)){
           $rules['cost'] = 'required|numeric';
           $rules['price'] = 'required|numeric';
        }else{
            $requestData['cost'] =  0;
            $requestData['price'] = 0;
        }

        $this->validate($request, $rules);


        $product = Product::with([
                    'product_images',
                    'store_products',
                    'product.store_products.store',
                    'product.product_attributes.variant',
                    'product_variants'
                ])->findOrFail($id);

        $requestData['is_main_price'] = empty($requestData['is_main_price']) ? 0 : 1;
        $requestData['is_main_tax'] = empty($requestData['is_main_tax']) ? 0 : 1;
        $product->update($requestData);

        // remove product tags
        ProductTag::where('product_id',$product->id)->delete();

        if(!empty($request->tags)){
            $tags = explode(',', $request->tags);
            foreach($tags as $tag){
                $tag_data['product_id'] = $product->id;
                $tag_data['name'] = $tag;
                ProductTag::create($tag_data);
            }
        }

        //save product image
        if($request->hasFile('image')){
            $destinationPath = public_path('uploads/products'); // upload path
            $image = $request->file('image'); // file
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $fileName = $product->id.'-'.str_random(10).'.'.$extension; // renameing image

            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/thumbs/'.$fileName);

            $image->move($destinationPath, $fileName); // uploading file to given path

            /*unlink old image*/
            if($product->product_images){
                @unlink(public_path("/uploads/products/".$product->product_images[0]->name));
                @unlink(public_path("/uploads/products/thumbs/".$product->product_images[0]->name));
            }

            //update image record
            $product_image['product_id'] = $product->id;
            $product_images = ProductImage::firstOrNew($product_image);
            $product_images->default = 1;
            $product_images->name = $fileName;
            $product_images->save();
        }

        // update store stock
        if($product->product->store_products){

            $product->product->store_products->map(function($store_product) use ($product, $requestData) {

                $new_quantity = empty($requestData['store-product-'.$store_product->id]) ? 0 : $requestData['store-product-'.$store_product->id];
                $store_id = $store_product->store->id;

                // Check store product exist or not
                $store_product_data = StoreProduct::where('product_id',$product->id)->where('store_id',$store_id)->first();
                if($store_product_data){

                    if($new_quantity > $store_product_data->quantity){
                       // stock add
                       $add_quantity = $new_quantity - $store_product_data->quantity;
                       updateProductStockByData($product->id, $store_id, $add_quantity, 1, 2, 0, 0, 'Edit Product');
                    }elseif($new_quantity < $store_product_data->quantity){
                       // stock remove
                       $remove_quantity = $store_product_data->quantity - $new_quantity;
                       updateProductStockByData($product->id, $store_id, $remove_quantity, 2, 2, 0, 0, 'Edit Product');
                    }
                }else{

                    // save new store product
                    $store_product_create['product_id'] = $product->id;
                    $store_product_create['store_id'] = $store_id;
                    $store_product_create['quantity'] = $new_quantity;

                    $store_products = StoreProduct::create($store_product_create);

                    updateProductStockByData($product->id, $store_id, $new_quantity, 1, 1, 0, 0, 'Add Product');
                }

                return $store_product;
            });
        }

        // product variants
        if($product->product->product_variants){
            $product->product->product_attributes->map(function($product_attribute) use ($product, $requestData) {

                $product_variant = [];
                $product_variant['variant_id'] = $product_attribute->variant_id;
                $product_variant['product_id'] = $product->id;

                $product_variants = ProductVariant::firstOrNew($product_variant);
                $product_variants->name= $requestData['variant-name-'.$product_attribute->id];
                $product_variants->save();

                return $product_variant;

            });
        }



        Session::flash('success', 'Product successfully updated!');

        return redirect()->back()->with('message', 'Product successfully updated!'); //redirect('admin/products');
    }

    public function uploadCsvProducts()
    {
      Session::flash('success', 'Product successfully uploaded!');

      return redirect('admin/products');
    }

    public function productFeedBack()
    {
        if(\request()->ajax())
        {
            $prdoctReview = DB::table('product_reviews')->orderBy('created_at','desc')->get();

            return DataTables::of($prdoctReview) ->addColumn('prodcut_name',function ($pemission){
                $product = Product::find($pemission->product_id);
                if($product){
                    return $product->name;
                }else{
                    return '';
                }

            })->addColumn('action', function ($permission) {
                    $action = '';
                    if(Auth::user()->can('delete_comments'))
                        $action .= '<a href="delete-comment/'.Hashids::encode($permission->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Wholesaler"><i class="fa fa-lg fa-trash"></i></a>';

                    return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);

        }
        return view('admin.comments.index');
    }

    public function deleteFeedBack($id)
    {
        if(\Request::ajax())
        {
            $id = Hashids::decode($id)[0];
        }
        $product = DB::table('product_reviews')->find($id);

        if($product){

            DB::table('product_reviews')->where('id',$id)->delete();
            $response['message'] = 'Feedback  deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Feedback not exist against this id!';
            $status = $this->notFoundStatus;
        }

        return response()->json(['result'=>$response], $status);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return JS0N Response
     */
     public function makeCopy($id)
    {
        $id = decodeId($id);
        
        $product = Product::with(
        [
            'product_tags',
            'store_products',
            'category_products',
            'product_images'
        ])->findOrFail($id);
         
        $productData = $product->toArray();

        $copyProduct = Product::create($productData);
        if ($copyProduct) {
            foreach($product->product_tags as $productTag) {
                $copyProduct->product_tags()->create([
                    'name' => $productTag->name
                ]);
            }
            foreach($product->store_products as $storeProduct) {
                $storeProduct = $storeProduct->toArray();
                $copyProduct->store_products()->create($storeProduct);
            }
            foreach($product->category_products as $categoryProduct) {
                $categoryProduct = $categoryProduct->toArray();
                $copyProduct->category_products()->create($categoryProduct);
            }
            foreach($product->product_images as $productImage) {
                $productImage = $productImage->toArray();
                $copyProduct->product_images()->create($productImage);
            }
            
            Session::flash('success', 'Product copy successfully created!');
            return redirect($this->resource.'/'.Hashids::encode($copyProduct->id).'/edit');
        }
        
        Session::flash('error', 'Product copy not creat!');
        return redirect($this->resource);
    }
}
