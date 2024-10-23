<?php

use Illuminate\Support\Facades\Auth;

use App\Country;
use App\User;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\StoreProduct;
use App\Models\ProductImage;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\Categories;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Shipping;
use App\Models\Transaction;
use App\Models\Brand;
use App\Models\CouriersAssignment;
use App\Models\WholesellerWallet;
use App\Models\CouriersAssignmentDetail;
use App\Models\WholesellerPayment;

if (! function_exists('isActiveRoute')) {



    function isActiveRoute($route, $output = "active")

    {

        if (Route::current()->uri == $route) return $output;

    }

}



if (! function_exists('setActive')) {



    function setActive($paths,$class = TRUE)

    {

        foreach ($paths as $path) {



            if(Request::is($path . '*')){

              if($class)

                return ' class=active';

              else

                return ' active';

            }



        }



    }

}
if(!function_exists('checkNewOrder')){
    function checkNewOrder($type){
        return User::where(['type'=>$type , 'is_latest' =>1])->count();
    }
}

if (! function_exists('checkImage')) {



    function checkImage($path, $size=false)

    {

        $paths = explode('/', $path);

        if($paths[count($paths)-1]==""){
            if($size)
                return asset('uploads/no-image.png');
            else
                return asset('uploads/thumbs/no-image.png');
        }else{

        if (\File::exists(public_path('uploads/'.$path)))
           return asset('uploads/'.$path);
        else
            if($size)
                return asset('uploads/no-image.png');
            else
                return asset('uploads/thumbs/no-image.png');
        }
    }

}



if (! function_exists('areActiveRoutes')) {



    function areActiveRoutes(Array $routes, $output = "active")

    {

        foreach ($routes as $route)

        {

            if (Route::current()->uri == $route) return $output;

        }



    }



}



if (! function_exists('settingValue')) {



    function settingValue($key)

    {

        $setting = \DB::table('site_settings')->where('key',$key)->first();

        if($setting)

            return $setting->value;

        else

            return '';

    }

}



if (! function_exists('companySettingValue')) {



    function companySettingValue($column)

    {

        if(Auth::guard('company')->check())

           $setting = Company_setting::first();

        elseif(Auth::guard('api')->check()){

            $setting = Company_setting::where('company_id',getComapnyIdByUser())->first();

        }



        if($setting)

            return $setting->{$column};

        else

            return '';

    }

}



if (! function_exists('companySettingValueApi')) {



    function companySettingValueApi($column)

    {

        $setting = Company_setting::where('company_id',getComapnyIdByUser())->first();

        if($setting)

            return $setting->{$column};

        else

            return '';

    }

}



if (! function_exists('getCountries')) {



    function getCountries()

    {

        return Country::pluck('name', 'code')->prepend('Select Country','');

    }

}





if (! function_exists('getParentCategoriesDropdown')) {



    function getParentCategoriesDropdown()

    {

        return Categories::where('parent_id', 0)->pluck('category_name', 'id')->prepend('Root Category',0);

    }

}

if (! function_exists('getParentCategories')) {



    function getParentCategories($limit = 0)

    {
        $categories = Categories::where('parent_id', 0);

        if($limit>0)
            $categories->offset(0)->limit($limit);

        return $categories->pluck('name', 'id');

    }

}


if (! function_exists('getCurrencyDropdown')) {



    function getCurrencyDropdown()

    {

        return Currency::pluck('name', 'id')->prepend('Select Currency','');

    }

}



if (! function_exists('getCategoriesDropdown')) {



    function getCategoriesDropdown()

    {

        $store_ids = Store::pluck('id');



        return Categories::whereIn('store_id',$store_ids)->pluck('category_name', 'id')->prepend('Select Categories','');



    }

}



if (! function_exists('getStoreIds')) {



    function getStoreIds()

    {



        if(Auth::guard('company')->check())

           $store_ids = Store::pluck('id');

        elseif(Auth::guard('api')->check()){

            $store_ids = [Auth::user()->store_id];

        }



        return $store_ids;



    }

}



if (! function_exists('getStoresDropdown')) {

    function getStoresDropdown()
    {
        return Store::pluck('name', 'id')->prepend('Select Store','');
    }
}



if (! function_exists('getStoreDropdownHtml')) {



    function getStoreDropdownHtml()

    {

       // if(isset(Request::segment(4)))



        $stores = Store::pluck('name', 'id');





        $html = '<span class="pull-right col-lg-3" style="margin-top: -5px;">';

        $html .= '<select class="form-control select2" id="store_reports"> ';

        $html .= '<option value="">All Stores</option> ';



        foreach($stores as $key => $value){

            $html .= '<option value="'. Hashids::encode($key) .'" '.(Request::segment(4)== Hashids::encode($key)?'selected':'').'>'. $value .'</option> ';

        }



        $html .= '</select> ';



        return $html;

    }

}




if (!function_exists('decodeId')) {
    function decodeId($id){
        $id = @Hashids::decode($id)[0];
        if($id)
            return $id;

        return 0;

}
}

if (! function_exists('getStoresFilterDropdown')) {

    function getStoresFilterDropdown()
    {
        return Store::pluck('name', 'id')->prepend('Filter by store','');
    }
}

if (! function_exists('getDriversFilterDropdown')) {

    function getDriversFilterDropdown()
    {
        return User::where('user_type','driver')->pluck('name', 'id')->prepend('Filter by Driver','');
    }
}
if (! function_exists('getRidersFilterDropdown')) {

    function getRidersFilterDropdown()
    {
        return User::where('user_type','rider')->pluck('name', 'id')->prepend('Filter by Rider','');
    }
}


if (! function_exists('getProductsDropdown')) {



    function getProductsDropdown()

    {

        return Product::where('product_id',0)->pluck('name', 'id')->prepend('Select Product','');

    }

}



if (! function_exists('getSelectedProduct')) {



    function getSelectedProduct($product_id)

    {

        return Product::where('id',$product_id)->pluck('name', 'id');

    }

}



if (! function_exists('getVariants')) {

    function getVariantsDropdown()
    {
        return Variant::pluck('name', 'id')->prepend('Select attribute','');
    }
}



if (! function_exists('getSuppliersDropdown')) {

    function getSuppliersDropdown()
    {
        return Supplier::pluck('name', 'id')->prepend('Select Supplier','');
    }
}



if (! function_exists('getTaxRatesDropdown')) {



    function getTaxRatesDropdown()

    {

        return TaxRate::orderBy('id','asc')->pluck('name', 'id');

    }

}



if (! function_exists('getShippingDropdown')) {



    function getShippingDropdown()

    {

        return Shipping::orderBy('id','asc')->pluck('name', 'id');

    }

}



if (! function_exists('getRegions')) {



    function getRegions()

    {

        return Regions::get();

    }

}



if (! function_exists('getActiveLeagues')) {



    function getActiveLeagues($user_id)

    {

        $category_ids = Items::select('category_id')->where(['user_id' => $user_id])->groupBy('category_id')->pluck('category_id');

        $active_leagues = Categories::whereIn('id', $category_ids)->groupBy('parent_id')->count();

        $active_leagues = str_pad(($active_leagues),7,0,STR_PAD_LEFT);

        return $active_leagues;

    }

}



if (! function_exists('getActiveLeaguesByUserId')) {



    function getActiveLeaguesByUserId($user_id)

    {

        $category_ids = Items::select('category_id')->where(['user_id' => $user_id])->groupBy('category_id')->pluck('category_id');

        $active_leagues_ids = Categories::select('parent_id')->whereIn('id', $category_ids)->groupBy('parent_id')->pluck('parent_id');

        $active_leagues = Categories::whereIn('id', $active_leagues_ids)->get();



        return $activ_leagues;

    }

}



if (! function_exists('getActiveLeaguesList')) {



    function getActiveLeaguesList()

    {

        $category_ids = Items::select('category_id')->groupBy('category_id')->pluck('category_id');

        $active_leagues = Categories::with('category')->whereIn('id', $category_ids)->groupBy('parent_id')->get();



        return $active_leagues;

    }

}


if (! function_exists('getCategories')) {
    function getCategories()
    {
        $categories = Categories::with('subcategories')->where(['store_id'=>1,'parent_id'=>0])->orderBy('ordering','asc')->get();
        return $categories;
    }
}

if (! function_exists('getCategoryNameById')) {
    function getCategoryNameById($id)
    {
        $category = Categories::find($id);
        return @$category->name;
    }
}

if (! function_exists('getPositionInLeagues')) {



    function getPositionInLeagues($record_id)

    {

        $item_id = Items::find($record_id)->category_id;



        $query = "SELECT id, score,deleted_at, FIND_IN_SET( score,

                        (SELECT GROUP_CONCAT( score ORDER BY score DESC ) FROM items )

                    ) AS position

                    FROM items

                    WHERE category_id = ".$item_id." and id = ".$record_id."  and deleted_at is null ";



        $position = DB::select( DB::raw($query) );



        if(!empty($position)){

           $position = $position[0]->position;

        }else{

           $position = 0;

        }



        return str_pad(($position),7,0,STR_PAD_LEFT);



    }

}



if (! function_exists('getPositionInLeaguesByLeagueIdAndUserId')) {



    function getPositionInLeaguesByLeagueIdAndUserId($category_id,$user_id)

    {

        $item_ids = Categories::select('id')->where('parent_id', $category_id)->pluck('id');

        $item_ids = implode(',',$item_ids->toArray());



        $query = "SELECT id, score,user_id,deleted_at, FIND_IN_SET( score,

                        (SELECT GROUP_CONCAT( score ORDER BY score DESC ) FROM items where user_id = ".$user_id." and deleted_at is null)

                    ) AS position

                    FROM items

                    WHERE category_id IN (".$item_ids.") and user_id = ".$user_id."  and deleted_at is null ";



        $position = DB::select( DB::raw($query) );



        if(!empty($position)){

           $position = $position[0]->position;

        }else{

           $position = 0;

        }





        return $position;

    }

}



if (! function_exists('registeredUsers')) {



    function registeredUsers()

    {

        $user = User::where('type','!=','retailer')->count();

        return number_format($user);

    }

}



if (! function_exists('allRecords')) {



    function allRecords()

    {

        $records = Items::count();

        return number_format($records);

    }

}



if (! function_exists('get_ad_content')) {



    function getAdContent($id)

    {

        $resu = Ad::where('id', $id)->first();

        if($resu){



            $ad_detail = $resu->code;

            $base_url = \URL::to('/');

            $ad_content   =   str_replace('{asset}',$base_url,$ad_detail);

        }

        return $ad_content;

    }

}

if (! function_exists('AddToLastViewed')) {



    function AddToLastViewed($user_id, $item_id)

    {

        $input['user_id'] =  $user_id;

        $input['item_id'] =  $item_id;



        $last_viewed = LastViewed::firstOrNew($input);

        $last_viewed->updated_at = date('Y-m-d G:i:s');

        $last_viewed->save();

    }

}



if (! function_exists('GetLastViewed')) {



    function GetLastViewed()

    {

        if(Auth::id()){

          $user_id =  Auth::id();

        $GetLastViewed = LastViewed::with(['item.category.region','item.record_images'])->where(['user_id' => $user_id])->orderBy('updated_at','desc')->take(5)->get();

        $GetLastViewed->map(function ($item) {



            $item->item->record_images->map(function ($image) {

                $image['record_thumbnail'] = checkImage('items/thumbs/'. $image->name);

                $image['record_image'] = checkImage('items/'. $image->name);

            });



            return $item;

        });

        }else{

            $GetLastViewed = [];

        }

        return $GetLastViewed;

    }

}



if (! function_exists('updateUserScore')) {



    function updateUserScore($user_id)

    {

        $score = Items::select('score')->where('user_id' , $user_id)->pluck('score');

        $user_score = str_pad($score->sum(),7,0,STR_PAD_LEFT);

        $update_score['user_score'] = $user_score;

        $user = User::findOrFail($user_id);

        $user->update($update_score);

    }

}



if (! function_exists('getProductDefaultImage')) {

    function getProductDefaultImage($product_id, $size=false)
    {

        $product_images = ProductImage::where('product_id',$product_id);

        if($product_images->count()>0){
            $product_image = $product_images->where('default',1)->first();

            if(!$product_image)
               $product_image = ProductImage::where('product_id',$product_id)->first();

                if($size)
                    return checkImage('products/'.$product_image->name);
                else
                   return checkImage('products/thumbs/'.$product_image->name);
        }else{
            return checkImage('products/no-image.jpg');
        }

    }



}

if (! function_exists('getProductQuantity')) {

    function getProductQuantity($product_id, $size=false)
    {

        $productCount = StoreProduct::where('product_id',$product_id)->first();

        if($productCount){
            return $productCount->quantity;
        }else{
            return 0;
        }

    }



}



if (! function_exists('totalSales')) {
    function totalSales()
    {
        //$store_ids = Store::pluck('id');

        $orders = Transaction::count();
        return number_format($orders);

    }
}



if (! function_exists('totalStores')) {
    function totalStores()
    {
        $stores = Store::count();
        return number_format($stores);
    }
}

if (! function_exists('totalProducts')) {
    function totalProducts()
    {
        $products = Product::where('product_id',0)->count();
        return number_format($products);
    }
}

if (! function_exists('totalSuppliers')) {
    function totalSuppliers()
    {
        $supplier = Supplier::count();
        return number_format($supplier);
    }
}

if (! function_exists('totalBrands')) {
    function totalBrands()
    {
        $brand = Brand::count();
        return number_format($brand);
    }
}

if (! function_exists('totalCategories')) {

    function totalCategories()
    {
        $categories = Categories::count();

        return number_format($categories);

    }

}



if (! function_exists('totalStocks')) {

    function totalStocks()

    {

        $assets = Stock::count();

        return number_format($assets);

    }

}



if (! function_exists('totalActiveStudents')) {



    function totalActiveStudents()

    {





        $students = Student::where('status',1)->count();



        return number_format($students);



    }



}



if (! function_exists('totalFees')) {



    function totalFees()

    {

        $student_fees = Student::where('status',1)->sum('pay_fees');



        return number_format($student_fees);



    }



}



if (! function_exists('totalFeeReceived')) {



    function totalFeeReceived()

    {

        $student_fees = Student_fee::whereMonth('month', '=', date('m'))->where('status',1)->sum('pay_fees');



        return number_format($student_fees);



    }



}



if (! function_exists('totalFeePending')) {



    function totalFeePending()

    {

        $student_ids = Student_fee::whereMonth('month', '=', date('m'))->where('status',1)->pluck('student_id');



        $student_fees = Student::whereNotIn('id',$student_ids)->where('status',1)->sum('pay_fees');



        return number_format($student_fees);



    }



}



if (! function_exists('totalDiscount')) {



    function totalDiscount()

    {

        $student_fees = Student_fee::whereMonth('month', '=', date('m'))->where('status',1)->sum('discount');



        return number_format($student_fees);



    }



}



if (! function_exists('totalArrears')) {



    function totalArrears()

    {

        $student_fees = Student_fee::whereMonth('month', '=', date('m'))->where('status',1)->sum('arrears');



        return number_format($student_fees);



    }



}



if (! function_exists('totalPrintings')) {



    function totalPrintings()

    {

        $student_fees = Student_fee::whereMonth('month', '=', date('m'))->where('status',1)->sum('printing');



        return number_format($student_fees);



    }



}



if (! function_exists('getComapnyIdByUser')) {



    function getComapnyIdByUser()

    {



        $user = User::with(['store.company'])->find(Auth::id());



        return $user->store->company->id;



    }



}



if (! function_exists('getVariantData')) {

    function getVariantData($product_id, $attribute_id, $return = 'id')
    {

        $product_variant = ProductVariant::where('product_id',$product_id)->where('variant_id',$attribute_id)->first();

        if($product_variant){
            if($return == 'id')
                return $product_variant->id;
            else
                return $product_variant->name;
        }

        return '';

    }



}



if (! function_exists('getStoreProductsData')) {

    function getStoreProductsData($product_id, $store_id, $return = 'id')
    {

        $store_product = StoreProduct::where('product_id',$product_id)->where('store_id',$store_id)->first();

        if($store_product){
            if($return == 'id')
                return $store_product->id;
            else
                return $store_product->quantity;
        }

        return '';
    }
}



if (! function_exists('updateProductStock')) {

    function updateProductStock($product_id, $store_id)
    {
        $stocks = Stock::where('product_id',$product_id)->where('store_id',$store_id)->get();
        if($stocks){
            $quantity = 0;

            foreach($stocks as $stock){

                if($stock->stock_type == 1)
                    $quantity = $quantity + $stock->quantity;
                elseif($stock->stock_type == 2)
                    $quantity = $quantity - $stock->quantity;
            }


            $stock_product = StoreProduct::where('product_id',$product_id)->where('store_id',$store_id);

            $stock_data = $stock_product->update(['quantity'=>$quantity]);
            if($stock_data){
                $stock_product_data = $stock_product->first();
                if($stock_product_data->quantity<1){
                   $stock_product->delete();
                }
            }
        }

    }

}



if (! function_exists('updateOrderProductsStock')) {



    function updateOrderProductsStock($order_id)

    {



        $order = Order::find($order_id);



        if($order){

            $products = json_decode($order->order_items);



            if(count($products)>0){

                foreach($products as $product){



                    $store_product = Store_products::where('product_id',$product->item_id)->where('store_id',$order->store_id)->first();

                    if($store_product){

                        updateProductStockByData($store_product->product_id, $store_product->store_id, $product->quantity, 2, 3, $order->id, Auth::id(), $order->order_note);



                        // item combos

                        if(!empty($product->item_combos)){

                            $item_combos = json_decode($product->item_combos);

                            if(count($item_combos)>0){

                                foreach($item_combos as $item_combo){

                                    $store_product = Store_products::where('product_id',$item_combo->id)->where('store_id',$order->store_id)->first();

                                    if($store_product){

                                        updateProductStockByData($store_product->product_id, $store_product->store_id, 1, 2, 3, $order->id, Auth::id(), $order->order_note);

                                    }

                                }

                            }

                        }



                        // item modifiers

                        if(!empty($product->item_modifiers)){

                            $item_modifiers = json_decode($product->item_modifiers);

                            if(count($item_modifiers)>0){

                                foreach($item_modifiers as $item_modifier){

                                    $store_product = Store_products::where('product_id',$item_modifier->id)->where('store_id',$order->store_id)->first();

                                    if($store_product){

                                        updateProductStockByData($store_product->product_id, $store_product->store_id, 1, 2, 3, $order->id, Auth::id(), $order->order_note);

                                    }

                                }

                            }

                        }



                }

                }

            }



        }

    }



}



if (! function_exists('updateProductStockByData')) {



    function updateProductStockByData($product_id, $store_id, $quantity, $stock_type, $origin, $order_id = 0, $user_id = 0, $note = NULL)

    {

        $store_data['order_id'] = $order_id;
        $store_data['product_id'] = $product_id;
        $store_data['store_id'] = $store_id;
        $store_data['user_id'] = $user_id;
        $store_data['quantity'] = $quantity;
        $store_data['stock_type'] = $stock_type;
        $store_data['origin'] = $origin;
        $store_data['note'] = $note;

        $stock = Stock::create($store_data);
        if($stock)
            updateProductStock($product_id, $store_id);

    }



}



if (! function_exists('find_key_value')) {



    function find_key_value($array, $key, $val)

    {

        foreach ($array as $item)

        {

            if (is_array($item) && find_key_value($item, $key, $val)) return true;



            if (isset($item[$key]) && $item[$key] == $val) return true;

        }



        return false;

    }



}



if (! function_exists('getProductTagline')) {



    function getProductTagline($product_id)

    {

        $tagline = '';

        $product = Product::with(['product_variants.product_attribute.variant'])->find($product_id);



        if($product->product_id>0){

            foreach($product->product_variants as $key => $product_variant){

                if($key==0)

                    $tagline .= $product_variant->name .' '.$product_variant->product_attribute->variant->name;

                else

                    $tagline .= ', '.$product_variant->name .' '.$product_variant->product_attribute->variant->name;



            }

        }


        if(!empty($tagline))

            $tagline = '<p>'.$tagline.'</p>';



        return $tagline;







    }



}



if (! function_exists('sendOrderEmail')) {



    function sendOrderEmail($order_id)

    {



        $order = Order::find($order_id);



        if($order){



            $email_data = Email_template::where('key','sale')->first();



            $customer = Customer::find($order->customer);



            if($customer){



                $email_to = $customer->email;

                $email_body = $email_data->content;



                $email_body = str_replace('{name}',$customer->first_name.' '.$customer->last_name,$email_body);

                $email_body = str_replace('{company}',$customer->company_name,$email_body);

                $email_body = str_replace('{reference_number}',$order->reference,$email_body);

                $email_body = str_replace('{site_name}',settingValue('site_title'),$email_body);

                $body = $email_body;

                Email_template::sendEmail($email_to,$email_data,$body);

            }



            if(companySettingValue('sales_notifications')==1){



                $email_to = companySettingValue('email');

                $email_body = $email_data->content;



                $email_body = str_replace('{name}',$customer->first_name.' '.$customer->last_name,$email_body);

                $email_body = str_replace('{company}',$customer->company_name,$email_body);

                $email_body = str_replace('{reference_number}',$order->reference,$email_body);

                $email_body = str_replace('{site_name}',settingValue('site_title'),$email_body);

                $body = $email_body;

                Email_template::sendEmail($email_to,$email_data,$body);

            }







        }

    }



}



if (! function_exists('convertNumberToWord')) {



    function convertNumberToWord($num = false)

    {

        $num = str_replace(array(',', ' '), '' , trim($num));

        if(! $num) {

            return false;

        }

        $num = (int) $num;

        $words = array();

        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',

            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'

        );

        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');

        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',

            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',

            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'

        );

        $num_length = strlen($num);

        $levels = (int) (($num_length + 2) / 3);

        $max_length = $levels * 3;

        $num = substr('00' . $num, -$max_length);

        $num_levels = str_split($num, 3);

        for ($i = 0; $i < count($num_levels); $i++) {

            $levels--;

            $hundreds = (int) ($num_levels[$i] / 100);

            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');

            $tens = (int) ($num_levels[$i] % 100);

            $singles = '';

            if ( $tens < 20 ) {

                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );

            } else {

                $tens = (int)($tens / 10);

                $tens = ' ' . $list2[$tens] . ' ';

                $singles = (int) ($num_levels[$i] % 10);

                $singles = ' ' . $list1[$singles] . ' ';

            }

            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );

        } //end for loop

        $commas = count($words);

        if ($commas > 1) {

            $commas = $commas - 1;

        }



        $words = implode(' ', $words) . ' only';



        return $words;

    }

}

if (! function_exists('getSliders')) {

    function getSliders()
    {
        return Slider::whereStatus('1')->orderBy('ordering','asc')->get();
    }
}

if (! function_exists('getProductDetails')) {

    function getProductDetails($id)
    {dd($id);
        return Product::with('tax_rate')->with('courier')->whereId($id)->first();
    }
}

if (! function_exists('getWholsellerDataWallet')) {

    function getWholsellerDataWallet($id)
    {
         $debit = WholesellerWallet::where('user_id',$id)->sum('debit');
         $credit = WholesellerWallet::where('user_id',$id)->sum('credit');
        $debit = ($debit)?$debit:0;
        $credit = ($credit)?$credit:0;

        return ($credit-$debit);
    }
}

if (! function_exists('getWholsellerPaymentAmount')) {

    function getWholsellerPaymentAmount($id)
    {
        $debit = WholesellerPayment::where('user_id',$id)->sum('amount');
        return $debit;
    }
}

if (! function_exists('attachPriceWholeseller')) {

    function attachPriceWholeseller($products)
    {



        return $products;
    }
}

if (! function_exists('showDiscount')) {

    function showDiscount($product)
    {
        if(Auth::check() && Auth::guard('web')->check()) {
            if(Auth::user()->type != 'retailer') {
                return false;
            }
        }

        if($product->discount > 0){
            if($product->discount_type == 1) {
                return $product->discount.' %';
            } elseif($product->discount_type == 2) {
                return $product->discount.' %';
            } else {
                return false;
            }
        }
    }
}

if (! function_exists('getDiscountedPrice')) {
    function getDiscountedPrice($product)
    {
        $discount   = $product->discount;
        $price      = $product->price;
        if($product->product_id > 0 && $product->is_main_price > 0) {
            $product    = Product::where('id', $product->product_id)->first();
            $discount   = $product->discount;
            $price      = $product->price;
        }

        if(Auth::check() && Auth::guard('web')->check()) {
            $user = Auth::user();
//                if($user->type == 'wholesaler') {
//                    $price_perc = $user->price_percentage;
//                    if($user->wholesaler_type=='1')
//                        $price_perc = settingValue('wholesaler_percentage');
//
//                    if($price_perc>0)
//                        $price = $product->price + ($product->price * $price_perc/100);
//                }
            if($user->type == 'dropshipper' ) {
                $price = $product->cost + ($product->cost * $user->percentage_1/100);
                return number_format($price,2);
            }else if($user->type == 'wholesaler'){
                $totalPrice = $product->cost * Auth::user()->mark_up/100;

                return number_format(($product->cost + $totalPrice),2,'.','');
            }
        }

        if($product->discount_type=='1'){
            if($discount>0){
                $price = $price-($price*$discount/100);
            }
        }elseif($product->discount_type=='2'){
            $price = $price-$discount;
        }
        return number_format($price,2);
    }
}

if (! function_exists('getDefaultVariant')) {
    function getDefaultVariant($product_id)
    {
        $product_default_variant = Product::where(['id' => $product_id])->orderBy('id','asc')->first();
        return $product_default_variant;
    }
}
if(! function_exists('getVatCharges')){
    function getVatCharges(){
        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;
        return $vatCharges;
    }
}
if (! function_exists('getDefaultCurrency')) {
    function getDefaultCurrency()
    {

        $currency = Currency::find(settingValue('currency_id'));
        return @$currency;
    }
}

if (! function_exists('addShippingCharges')) {
    function addShippingCharges($product,$type)
    {
        if( $type == 'dropshipper'){
            $product = Product::with('courier')->where('id', $product->id)->first();

            return ['name' => ($product->courier)?$product->courier->name:'Free Shipping', 'charges' => ($product->courier)?$product->courier->charges:0];
        }


        return ['name' => ($product->shipping)?$product->shipping->name:'Free Shipping', 'charges' => ($product->shipping)?$product->shipping->charges:0];
    }
}

if (! function_exists('string_replace')) {
    function string_replace($str)
    {
        $slug = \Str::slug($str, '-');
        return $slug;
    }
}


if(!function_exists('getAdminPendingRequests')){
    function getAdminPendingRequests(){
        return CouriersAssignment::where('status',1)->count();
    }
}
if(!function_exists('courierDetailData')){
  function courierDetailData($id){

        $courier_assign = CouriersAssignment::where('cart_id',$id)->first();
        return $courier_assign;
}
}

if(!function_exists('courierDetailDataCharges')){
  function courierDetailDataCharges($id,$cart_id){

          $courierAssignmentDetail = CouriersAssignmentDetail::with('couriers')->where('product_id', '=', $id)->where('cart_id',$cart_id)->first();
        return $courierAssignmentDetail;
}
}

if(!function_exists('getOrderProductTaxAmount')){
    function getOrderProductTaxAmount($purchasedItems, $productId){
        $productVat = 0;
        $products = collect($purchasedItems);
        $product = $products->where('product_id', $productId)->first();
        if ($product && @$product->product) {
            $taxId = $product->product->tax_rate_id;
            $vatCharges = TaxRate::select('rate')->where('id', $taxId)->first();
            if ($vatCharges) {
                $vatCharges = (int)$vatCharges->rate;
                $productVat = ($product->amount_per_item * $product->quantity) * ($vatCharges / 100);
            }

        }
        return $productVat;
    }
}

if(!function_exists('productDefaultCourier')){
  function productDefaultCourier($id,$shipping_id){

          $product = Product::find($id);
          $product->shipping_id = $shipping_id;
          $product->save();
        return $product;
}
}


