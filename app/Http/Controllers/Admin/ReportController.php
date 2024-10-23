<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Response;
use Image;
use File;
use Session;
use Alert;
use DataTables;
use Hashids;
use App\User;
use App\Models\Transaction;
use App\Models\Product;
use App\Store;
use App\Models\StoreProduct;
use App\Stock;
use App\Order;
use App\Customer;
use DB;

class ReportController extends Controller
{

    public function __construct()
   {
        $this->middleware('permission:view reports');        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function index(Request $request)
    {     
    
        if($request->ajax())
        {

            $orders = Transaction::query();
            
            // if(!empty($request->store_id)){
            //     $store_id = Hashids::decode($request->store_id)[0];  
            //     $orders->where('store_id', $store_id);
            // }
            
            if(!empty($request->from_date)){
                $orders->where('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)).' 00:00:00');
            }

            if(!empty($request->to_date)){
                $orders->where('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)).' 23:59:59');
            }
            
            $orders->get();        
                                  
            $total_orders = $orders->get();
            $sub_total = $orders->sum('amount');
            // $cost_of_goods = $orders->sum('cost_of_goods');
            $cost_of_goods = $orders->sum('amount');
            // $discount = $orders->sum('discount');
            $discount = 0;
            
            $report['total_income'] = number_format($sub_total,2);
            $report['total_sales'] = $orders->count();
            $report['total_profit'] = number_format($sub_total-$cost_of_goods,2);    
            $report['total_discount'] = number_format($discount,2); 
            $report['discount_percentage'] = 0;        
            
            if($discount>0 )
                $report['discount_percentage'] = $orders->sum('sub_total')/$orders->sum('discount');
            
            $report['basket_value'] = number_format($orders->avg('amount'),2);
            //$report['basket_size'] = number_format($orders->avg('basket_size'),2);           
            $report['basket_size'] = 10;           
            $report['cash_sales'] = number_format($total_orders->sum('amount'),2);
            $report['card_sales'] = number_format($total_orders->sum('amount'),2);
            $report['total_customers'] = $total_orders->groupBy('user_id')->count();                   
                         
            $status = $this->successStatus;
                
            return response()->json(['result' => $report], $status);
                
        }     
        return view('admin.reports.index');
    }        
       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function stocksChart($id = '')
    {
                
        if(empty($id)){
          $store_products = StoreProduct::get();   
        }else{
            $store_id = decodeId($id);  
            $store_products = StoreProduct::where('store_id',$store_id)->get();  
        }

        $products = Product::with(['store_products'])->whereIn('id',$store_products->pluck('product_id'))->get();
          
        $total_products = $products->count();
        $total_product_quantity = $store_products->sum('quantity');
        
        $total_product_cost=0;
        $total_product_price=0;
        
        foreach($products as $product){
            $total_product_cost = $total_product_cost + ($product->cost * $product->store_products->sum('quantity'));
            $total_product_price = $total_product_price + ($product->price * $product->store_products->sum('quantity'));
        }
        
        $profit_estimate = $total_product_price - $total_product_cost;
        
        $report['total_products'] = $total_products;
        $report['total_product_quantity'] = $total_product_quantity;
        $report['total_product_cost'] = $total_product_cost;
        $report['total_product_price'] = $total_product_price;
        $report['profit_estimate'] = $profit_estimate;        
        
        return view('admin.reports.stock', compact('report'));
            
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function saleReport(Request $request)
    {                     
        if($request->ajax())
         {
            $orders = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('SUM(amount) as cost_of_goods'),
                DB::raw('SUM(amount) as order_tax')
            );     
            
            if(!empty($request->from_date)){
                $orders->where('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)).' 00:00:00');
            }

            if(!empty($request->to_date)){
                $orders->where('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)).' 23:59:59');
            }
            
            $orders = $orders->groupBy('date')->orderBy('date', 'desc');                       
            
            
            return DataTables::of($orders)
                ->addColumn('date', function ($order) {
                    return date('d/m/Y', strtotime($order->date));                
                })
                ->addColumn('revenue', function ($order) {
                    return number_format($order->revenue, 2, '.', '');
                })
                ->addColumn('cost_of_goods', function ($order) {
                    // return number_format($order->cost_of_goods, 2, '.', '');
                     return number_format(100, 2, '.', '');
                })
                ->addColumn('gross_profit', function ($order) {
                    //return number_format($order->revenue - $order->cost_of_goods, 2, '.', '');
                    return number_format($order->revenue - 100, 2, '.', '');
                })
                ->addColumn('margin', function ($order) {
                    //return number_format(($order->cost_of_goods / $order->revenue)*100, 2, '.', '');
                    return number_format((100 / $order->revenue)*100, 2, '.', '');
                })
                ->addColumn('order_tax', function ($order) {
                    //return number_format($order->order_tax, 2, '.', '');
                    return number_format(100, 2, '.', '');
                })
                ->rawColumns(['gross_profit', 'margin'])
                ->make(true);
                
        }   
        // $orders = Transaction::select(
        //                     DB::raw('DATE(created_at) as date'),
        //                     DB::raw('SUM(sub_total) as revenue'),
        //                     DB::raw('SUM(cost_of_goods) as cost_of_goods'),
        //                     DB::raw('SUM(order_tax) as order_tax')
        //         );  
        $orders = Transaction::select(
                            DB::raw('DATE(created_at) as date'),
                            DB::raw('SUM(amount) as revenue'),
                            DB::raw('SUM(amount) as cost_of_goods'),
                            DB::raw('SUM(amount) as order_tax')
                );                                           
           
        $orders = $orders->groupBy('date')->orderBy('date', 'desc')->get();
        
        return view('admin.reports.sales');
    } 

    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function productReport(Request $request)
    {    

        if($request->ajax()){
            
            $products = StoreProduct::with(['product.store_products'])->orderBy('id','asc')->get();
            $orders = Transaction::with('cart');
            
            if(!empty($request->from_date)){
                $orders->where('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)).' 00:00:00');
            }

            if(!empty($request->to_date)){
                $orders->where('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)).' 23:59:59');
            }

            $orders = $orders->get();

            $products->map(function ($product) use ($orders) {    

                $product['code'] = $product->product->code;
                $product['sku'] = $product->product->sku;                                
                $product['current_stock'] = $product->product->store_products->sum('quantity');
                $product['item_value'] = $product->product->price;
                $product['stock_value'] = $product->product->store_products->sum('quantity')*$product->product->price;
                      
                $reorder_point = 0;
                $reorder_amount = 0;
                foreach($orders as $order){
                    
                    $order_products = unserialize(@$order->cart->cart_details);
                    if(count($order_products)>0){  
                        
                        $product_collection = collect($order_products);                                     
                        $filtered = $product_collection->where('id', $product->product->id)->first();
                        
                        if($filtered){ 
                           $reorder_point = $reorder_point + 1;  
                           $reorder_amount = $reorder_amount + $filtered['price'];                                               
                        }
                                                              
                    }
                }
                
                $product['reorder_point'] = $reorder_point;
                $product['reorder_amount'] = $reorder_amount;
                
                return $product;
            });

            $products = $products->unique('product_id');            
       
            return DataTables::of($products)
                ->addColumn('name', function ($product) {
                    if($product->product->product_id==0)
                        return '<a href="'. url('admin/products/'. Hashids::encode($product->product->id).'/edit') .'" class="text-info" target="_blank">'. $product->product->name .'</a>';
                    else    
                        return '<a href="'. url('admin/products/edit/'. $product->product->id) .'" class="text-info" target="_blank">'. $product->product->name .'</a>';
                        
                })           
                ->rawColumns(['name'])
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);
        }
        
       
        return view('admin.reports.products');
    } 

    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function customerReport(Request $request)
    {   

        if($request->ajax())
        {          
            $customers = User::with('transactions');          
            
            if(!empty($request->store_id)){
                $store_id = Hashids::decode($request->store_id)[0];  
                $customers->where('store_id',$store_id);
            }
            
            $customers = $customers->orderBy('id','asc')->get();
            
            $customers->map(function ($customer) use ($request) { 
                    
                    $customer_orders = $customer->transactions->where('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)).' 00:00:00')->where('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)).' 23:59:59');                
                
                    $customer['total_sales'] = $customer_orders->count('id');
                    $customer['total_sale_amount'] = number_format($customer_orders->sum('amount'), 2, '.', '');
                 
                unset($customer->transactions);    
                return $customer;
            });
            
            $customers = $customers->all();                         
            
            return DataTables::of($customers)
                ->addColumn('store_name', function ($customer) {
                    return @$customer->store->name;                    
                })                              
                ->rawColumns(['name'])
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);            
        }

        return view('admin.reports.customers');
    } 
    
    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function staffReport()
    {              
        return view('company.reports.staff');
    } 
    
    /**
     * Display a listing of the resource.
     *
     * @return JSON
     */
    public function getStaffReport(Request $request)
    {          
        $users = User::with(['orders'])->whereIn('store_id', getStoreIds());          
        
        if(!empty($request->store_id)){
            $store_id = Hashids::decode($request->store_id)[0];  
            $users->where('store_id',$store_id);
        }
        
        $users = $users->orderBy('id','asc')->get();
        
        $users->map(function ($user) use ($request) { 
                
                $user_orders = $user->orders->where('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)).' 00:00:00')->where('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)).' 23:59:59');                
            
                $user['total_sales'] = $user_orders->count('id');
                $user['total_sale_amount'] = number_format($user_orders->sum('order_total'), 2, '.', '');
                $user['total_discount'] = number_format($user_orders->sum('discount'), 2, '.', '');          
                $user['total_tax'] = number_format($user_orders->sum('order_tax'), 2, '.', '');          
             
            unset($user->orders);    
            return $user;
        });
        
//        $users = $users->filter(function($item) {
//            return $item->total_sales != 0;
//        });
       
        
        $users = $users->all();                         
        
        return Datatables::of($users)                              
            ->rawColumns([])
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);            
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return JS0N Response
     */
    public function productStocks($product_id)
    {    
        $product_id = Hashids::decode($product_id)[0];  
        
        $product = Product::find($product_id);
        
        return view('company.reports.stores_stock', compact('product'));
    }  
    
    /**
     * Display a listing of the resource.
     *
     * @return JSON
     */
    public function getReportsGraphApi(Request $request)
    {
        if(\Request::wantsJson()) 
        {
            
            $orders = Order::where('store_id',Auth::user()->store_id);
            
            if(empty($request->from_date) && empty($request->to_date)){
                $orders->where('created_at', '>=' , date('Y-m-d', strtotime('-1 years')).' 00:00:00');
                $orders->where('created_at', '<=' , date('Y-m-d').' 23:59:59');
            }else{
                if(!empty($request->from_date)){
                    $orders->where('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)).' 00:00:00');
                }

                if(!empty($request->to_date)){
                    $orders->where('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)).' 23:59:59');
                }
            }
            
            $orders->groupBy('x')->orderBy('x', 'asc');
            
            switch ($request->type) {
                case 1: //Total Sales
                    $total_sales = $orders->get(array( DB::raw('Date(created_at) as x'), DB::raw('SUM(order_total) as y')));     
                    $total_transactions = $orders->get(array( DB::raw('Date(created_at) as x'), DB::raw('COUNT(id) as y')));                     
                    break;
                case 2: //Cash Sales    
                    $total_sales = $orders->where('payment_method',1)->get(array( DB::raw('Date(created_at) as x'), DB::raw('SUM(order_total) as y')));     
                    $total_transactions = $orders->where('payment_method',1)->get(array( DB::raw('Date(created_at) as x'), DB::raw('COUNT(id) as y')));     
                    break;
                case 3: //Card Sales   
                    $total_sales = $orders->where('payment_method',2)->get(array( DB::raw('Date(created_at) as x'), DB::raw('SUM(order_total) as y')));     
                    $total_transactions = $orders->where('payment_method',2)->get(array( DB::raw('Date(created_at) as x'), DB::raw('COUNT(id) as y')));     
                    break;
                case 4: // Avg Basket Size 
                    $total_sales = [];     
                    $total_transactions = $orders->get(array( DB::raw('Date(created_at) as x'), DB::raw('AVG(basket_size) as y')));     
                    break;
                case 5: // Avg Basket Value   
                    $total_sales = $orders->get(array( DB::raw('Date(created_at) as x'), DB::raw('AVG(order_total) as y')));     
                    $total_transactions = [];   
                    break;
                case 6: // Discount 
                    $total_sales = $orders->get(array( DB::raw('Date(created_at) as x'), DB::raw('SUM(discount) as y')));     
                    $total_transactions = $orders->get(array( DB::raw('Date(created_at) as x'), DB::raw('COUNT(id) as y')));     
                    break;                
                default:
                    $total_sales = [];
                    $total_transactions = [];
            }
            
            $report['total_sales'] = $total_sales;
            $report['total_transactions'] = $total_transactions;
            
            $status = $this->successStatus;

            return response()->json(['result' => $report], $status);
        }    
    }        
    
}
