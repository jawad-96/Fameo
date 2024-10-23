<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Newsletter_subscriber;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Categories;
use App\Models\CategoryProduct;
use App\Models\ProductView;
use App\Models\Promotion;
use Hashids;
use Minioak\MyHermes\MyHermes;
use Illuminate\Support\Facades\DB;

use Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $class  = 'style1';
        $class  = 'background';
        $page   = 'home';
        // $footer = 'style2';
        $footer = '';

        // $product_views = Product::with(['category_products.category','product_views'])->withCount('product_views')
        //             ->whereHas('product_views',function($q){
        //                 $q->where('product_id','>',0);
        //             })->where(['product_id' => 0])->orderBy('product_views_count','desc')->limit(10)->get();
        // $promotion = Promotion::with('products')->where('start_time','<=',now())->orderBy('start_time','asc')->first();
        $topCategories = Categories::withCount('category_products') // Count the related category products
            ->orderBy('category_products_count', 'desc') // Order by the count of category products
            ->limit(6)
            ->get();
        $trendingItems = CategoryProduct::join('products', 'category_products.product_id', '=', 'products.id')
        ->join('product_views', 'category_products.product_id', '=', 'product_views.product_id')
        ->join('product_images', 'products.id', '=', 'product_images.product_id') // Join with product_images table
        ->select(
            'category_products.*',
            'products.name as product_name',
            'products.price as product_price', // Get the price of the product
            'product_images.name as product_image', // Get the product image
            DB::raw('COUNT(product_views.id) as total_views')
        )
        ->groupBy('category_products.id', 'products.name', 'products.price', 'product_images.name') // Include product image in group by
        ->orderBy('total_views', 'desc') // Order by most views
        ->limit(5) // Limit the result to the top 10 trending items
        ->get();
        $featuredItems = Product::inRandomOrder()
            ->select('products.*', 'product_images.name as product_image') // Assuming `image_path` is the column for the image
            ->join('product_images', 'products.id', '=', 'product_images.product_id')
            ->with([
                'category_products.category',
                'store_products',
                'isFavorite',
            ])
            ->where('is_featured', 1)
            ->orderBy('products.id', 'desc')
            ->limit(3)
            ->get();

            //$popularItemsWithTabs = $this->getPopularItemsWithTabs();
        //  dd($topCategories);
        //return view('home', compact('product_views','class', 'page', 'footer','promotion'));

        return view('home',compact('topCategories','trendingItems','featuredItems'));
    }

    public function get_home_products(Request $request)
    {
        $param = $request->param;
        if($param == 'new') {
            $products = Product::inRandomOrder()->with(['category_products.category', 'store_products', 'isFavorite'])->where(['product_id' => 0,'new_arrivals'=>'1'])->orderBy('id','desc')->limit(12)->get();
        } elseif($param == 'featured') {
            $products = Product::inRandomOrder()->with(['category_products.category', 'store_products', 'isFavorite'])->where(['product_id' => 0,'is_featured'=>'1'])->orderBy('id','desc')->limit(12)->get();
        } else {
            $products = Product::inRandomOrder()->with(['category_products.category', 'store_products', 'isFavorite'])->where(['product_id' => 0,'is_hot'=>'1'])->orderBy('id','desc')->limit(12)->get();
        }
        return view('partial_home_products', compact('products'));
    }

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

    // public function getPopularItemsWithTabs()
    // {
    //     // Fetch all popular items for the "All Items" tab
    //     $allPopularItems = Product::inRandomOrder()
    //         ->select('products.*', 'product_images.name as product_image')
    //         ->join('product_images', 'products.id', '=', 'product_images.product_id')
    //         ->with(['category_products.category', 'store_products', 'isFavorite'])
    //         ->where('is_hot', 1) // Filter for popular items
    //         ->limit(10) // Adjust as needed
    //         ->get();

    //     // Fetch categories dynamically, you can filter as needed
    //     $specificCategories = Categories::whereIn('name', function($query) {
    //         $query->select('name')
    //             ->from('categories')
    //             ->whereIn('name', ['Bedroom', 'Decoration', 'Living Room']); // Modify this based on your logic to fetch categories
    //     })->get();

    //     // Initialize an array to store popular items by category
    //     $popularItemsByCategory = [];

    //     // Loop through each specific category and get popular items
    //     foreach ($specificCategories as $category) {
    //         $popularItemsByCategory[$category->name] = Product::inRandomOrder()
    //             ->select('products.*', 'product_images.name as product_image', 'products.price')
    //             ->join('product_images', 'products.id', '=', 'product_images.product_id')
    //             ->with(['category_products.category', 'store_products', 'isFavorite'])
    //             ->where('is_hot', 1) // Filter for popular items
    //             ->whereHas('category_products.category', function ($q) use ($category) {
    //                 $q->where('id', $category->id);
    //             })
    //             ->limit(5) // Limit to 5 popular items per category
    //             ->get();
    //     }

    //     // Return all items and category-wise popular items
    //     return [
    //         'allPopularItems' => $allPopularItems, // All items for the first tab
    //         'popularItemsByCategory' => $popularItemsByCategory, // Items for the specific categories
    //         'specificCategories' => $specificCategories, // The dynamically fetched categories for the tabs
    //     ];
    // }



    public function test(){
        /*$user = User::whereEmail('wasim.iqtm@gmail.com')->first();
        $newsletter_user = Newsletter_subscriber::whereEmail('wasim.iqtm@gmail.com')->first();
        $user->delete();
        $newsletter_user->delete();

        echo "done";exit;*/


        $data = [
            'email_from' => 'info@aqsinternational.com',
            'email_to' => 'wasim.iqtm@gmail.com',
            'email_subject' => 'Newsletter Subscription',
            'user_name' => 'User',
            'url' => url('unsubscribe-newsletter?id='.Hashids::encode(1)),
            'final_content' => '<p>Dear {name}</p>
                                <p>Here is your unsubscription Link {url}</p>',
        ];
        Email::sendEmail($data);
        dd('done');
    }

    public function createParcel($user)
    {
        try {
            $hermes = new MyHermes('03becf7c-638f-4bae-9496-8c7070d119d4',false);
            $address =explode(' ',$user['address']);

            $mockData = [
                'firstName' =>  $user['first_name'],
                'lastName' => $user['last_name'],
                'weight' => '2',
                'description' => 'My Test Delivery Item',
                'email' => $user['email_address'],
                'postcode' => $user['post_code'],
                'addressLine1' => trim($address[0]??'')??'',
                'addressLine2' =>  trim($address[1]??'')??'',
                'addressLine3' => $user['state_country'],
                'addressLine4' => $user['country'],
                'value' => '120'

            ];


            $response = $hermes->parcels([$mockData]);

           // $response = $service->parcels([$mockData]);
            $barcode = '';
            $image = '';
            foreach ($response as $parcel) {
                $barcode = $parcel->barcode;
                $this->createBarcode($barcode);
            }

            $image ='uploads/harmes_labels/'.$barcode.'.png';
            return ['barcode' => $barcode,'image' => $image];
        } catch(Exception $e) {
          Log::error('MyHermes barcode error: ' .$e->getMessage());
          return ['barcode' => '','image' => ''];
        }
    }

    public function testBarcode($barcode)
    {
        $this->createBarcode($barcode);
    }

    private function createBarcode($barcode)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.myhermes.co.uk:443/api/labels/'.$barcode.'?format=THERMAL',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer  03becf7c-638f-4bae-9496-8c7070d119d4',
                    'Accept: image/png',
                    'Cookie: visid_incap_2011121=38PCrTSYSDapZLda1Tvure/Px18AAAAAQUIPAAAAAAAQ+EiFpjXjVzi/IJQReAFv; visid_incap_1996757=M18gU66+TG+cR6KjHkUJXsLsx18AAAAAQUIPAAAAAACV1B+03A4LVY81C0MBk6gB; nlbi_2011121=LrAyG/c320B4NaStYk7KeQAAAACRm6d27xQayqyFtq0/m1a4; incap_ses_960_2011121=BUPFU9VyJDt9R+B5GJtSDZLRz18AAAAAy6dadRVkO3eG/vL5p++ssg=='
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);


          file_put_contents( public_path('uploads/harmes_labels/'.$barcode.'.png'),$response);

        } catch(Exception $e) {
          Log::error('MyHermes label error: ' .$e->getMessage());
        }

    }

    public function getAuthCode()
    {
        try {
            $hermes = new MyHermes('03becf7c-638f-4bae-9496-8c7070d119d4',false);

            $mockData = [
                'firstName' => 'John',
                'lastName' => 'Mitchell',
                'weight' => '2',
                'description' => 'My Test Delivery Item',
                'email' => 'john@email.com',
                'postcode' => 'PH15HJ',
                'addressLine1' => '10 Dowling Street'

            ];


            $response = $hermes->parcels([$mockData]);


            // $response = $service->parcels([$mockData]);
            $barcode = '';
            $image = '';
            foreach ($response as $parcel) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.myhermes.co.uk:443/api/labels/'.$parcel->barcode.'?format=THERMAL',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer  03becf7c-638f-4bae-9496-8c7070d119d4',
                        'Accept: image/png',
                        'Cookie: visid_incap_2011121=38PCrTSYSDapZLda1Tvure/Px18AAAAAQUIPAAAAAAAQ+EiFpjXjVzi/IJQReAFv; visid_incap_1996757=M18gU66+TG+cR6KjHkUJXsLsx18AAAAAQUIPAAAAAACV1B+03A4LVY81C0MBk6gB; nlbi_2011121=LrAyG/c320B4NaStYk7KeQAAAACRm6d27xQayqyFtq0/m1a4; incap_ses_960_2011121=BUPFU9VyJDt9R+B5GJtSDZLRz18AAAAAy6dadRVkO3eG/vL5p++ssg=='
                    ),
                ));

                $response = curl_exec($curl);


                curl_close($curl);
                $barcode = $parcel->barcode;

                file_put_contents( public_path('uploads/harmes_labels/'.$parcel->barcode.'.png'),$response);


        }

        } catch(Exception $e) {
          Log::error('MyHermes barcode error: ' .$e->getMessage());
        }
    }
}
