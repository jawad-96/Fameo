<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hashids, DataTables;
use App\User;

class CustomerController extends Controller
{


    public function __construct()
   {
        $this->middleware('permission:view customers', ['only' => ['index']]);
        $this->middleware('permission:add customers', ['only' => ['create','store']]);
        $this->middleware('permission:edit customers', ['only' => ['edit','update']]);
        $this->middleware('permission:delete customers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $customers = User::where('type','retailer');

            return Datatables::of($customers)
                ->addColumn('name', function ($customer) {
                    return $customer->first_name.' '.$customer->last_name;
                })
                ->addColumn('profile_image', function ($customer) {
                    return '<img width="30" src="'.checkImage('customers/thumbs/'. $customer->profile_image).'" />';
                })
                ->addColumn('action', function ($order) {
                    return '<a href="orders?user_id='. Hashids::encode($order->id).'" target="_blank" class="btn btn-xs btn-warning">Orders</a>';
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['profile_image','action'])
                ->make(true);

        }
        return view('admin.customers.index');
    }

    public function retailerOrders()
    {
        if(request()->ajax()){
            $customers = User::where('type','retailer')->orderBy('updated_at','desc');

            return Datatables::of($customers)
                ->addColumn('name', function ($customer) {
                    $name = $customer->first_name . ' ' . $customer->last_name;
                    $color ='black';
                    if($customer->is_latest){
                        $color='red';
                    }
                    return "<a style='color:$color'>$name</a>";
                })   ->addColumn('email', function ($customer) {
                    $name = $customer->email;
                    $color ='black';
                    if($customer->is_latest){
                        $color='red';
                    }
                    return "<a style='color:$color'>$name</a>";
                })
                ->addColumn('profile_image', function ($customer) {
                    return '<img width="30" src="'.checkImage('customers/thumbs/'. $customer->profile_image).'" />';
                })
                ->addColumn('action', function ($order) {
                    return '<a href="orders?user_id='. Hashids::encode($order->id).'" target="_blank" class="btn btn-xs btn-warning">Orders</a> | <a href="' . url('admin/update-status/retailer/'.Hashids::encode($order->id)). '"  class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>';
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['profile_image','action','name','email'])
                ->make(true);

        }
        return view('admin.customers.retailer_orders');
    }
}
