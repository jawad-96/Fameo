<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\User;
use Illuminate\Http\Request;
use Session,Hashids,DataTables,DB;

class DropShipperController extends Controller
{

   public function __construct()
   {
        $this->middleware('permission:view drop_shipper', ['only' => ['index']]);
        $this->middleware('permission:add drop_shipper', ['only' => ['create','store']]);
        $this->middleware('permission:edit drop_shipper', ['only' => ['edit','update']]);
        $this->middleware('permission:delete drop_shipper', ['only' => ['destroy']]);
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        if($request->ajax()){

            $users = User::where('type','dropshipper')->orderBy('updated_at','desc');

            return DataTables::of($users)
                ->addColumn('is_active', function ($user) {
                 $satatus = '<a class="btn btn-xs btn-danger">Inactive</a>';
                 if($user->is_active=="yes")
                    $satatus = '<a class="btn btn-xs btn-success">Active</a>';

                    return $satatus;
                })
                ->addColumn('name', function ($user) {
                    $name = $user->full_name;
                    $color ='black';
                    if($user->is_latest){
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
                ->addColumn('wallet_amount', function ($user) {
                    return number_format(getWholsellerDataWallet($user->id),'2','.','');
                })
                ->addColumn('payment_amount', function ($user) {
                    return 0;
                    //return '<a class="text-primary" href="wholesaler/payments/'. Hashids::encode($user->id).'" >'.number_format(getWholsellerPaymentAmount($user->id),'2','.','').'</a>';
                })
                ->addColumn('action', function ($user) {
                $action = '';
                if(Auth::user()->can('edit drop_shipper'))
                    $action .= '<a href="drop-shipper/'. Hashids::encode($user->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Drop Shipper"><i class="fa fa-lg fa-edit"></i></a>';
                if(Auth::user()->can('delete drop_shipper'))
                    $action .= '<a href="drop-shipper/'.Hashids::encode($user->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Drop Shipper"><i class="fa fa-lg fa-trash"></i></a>';

                    $action .= '<a href="javascript:void(0)" class="text-primary walletAdd"  data-id="'.$user->id.'" data-toggle="tooltip" title="Add Wallet Amount"><i class="fa fa-google-wallet"></i></a>';
                
                    $action .= '<a href="javascript:void(0)" class="text-success receivePayment"  data-id="'.$user->id.'" data-toggle="tooltip" title="Receive Payment"><i class="fa fa-download"></i></a>';
                    
                    $action .= '<a href="javascript:void(0)" class="text-danger adjustOverdueAmount" data-id="'.Hashids::encode($user->id).'" data-toggle="tooltip" title="Adjust Overdue Amount"><i class="fa fa-upload"></i></a>';
                    
                    $action .= '<a href="drop-shipper/payments/'. Hashids::encode($user->id).'" class="text-danger" data-toggle="tooltip" title="Payment History"><i class="fa fa-history"></i></a>';

                    return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['is_active','action','payment_amount','name','email'])
                ->make(true);

        }

        return view('admin.drop-shipper.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.drop-shipper.create');
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

       $requestData = $request->all();
       $requestData['password'] = bcrypt($requestData['password']);
       $requestData['type'] = 'dropshipper';

       $user = User::create($requestData);

        Session::flash('success', 'Drop shipper added!');

        return redirect('admin/drop-shipper');
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

        $user = User::findOrFail($id);

        return view('admin.drop-shipper.edit', compact('user'));
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
        ]);

        $requestData = $request->all();
        $requestData['type'] = 'dropshipper';

        $user = User::findOrFail($id);
        $user->update($requestData);

        Session::flash('success', 'Drop shipper updated!');

        return redirect('admin/drop-shipper');
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

        $user = User::find($id);

        if($user){
            $user->delete();
            $response['message'] = 'Drop shipper deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Drop shipper not exist against this id!';
            $status = $this->errorStatus;
        }

        return response()->json(['result'=>$response], $status);

    }

    public function dropShipperOrders()
    {
        if(\request()->ajax()) {
            $users = User::where('type', 'dropshipper')->orderBy('updated_at','desc');
            return Datatables::of($users)
                ->addColumn('name', function ($customer) {
                    $name = $customer->first_name . ' ' . $customer->last_name;
                    $color ='black';
                    if($customer->is_latest){
                        $color='red';
                    }
                    return "<a style='color:$color'>$name</a>";
                })
                ->addColumn('email', function ($customer) {
                    $email = $customer->email;
                    $color ='black';
                    if($customer->is_latest){
                        $color='red';
                    }
                    return "<a style='color:$color'>$email</a>";
                })
                ->addColumn('profile_image', function ($customer) {
                    return '<img width="30" src="' . checkImage('customers/thumbs/' . $customer->profile_image) . '" />';
                })
                ->addColumn('action', function ($order) {
                    return '<a href="orders?user_id=' . Hashids::encode($order->id) . '" target="_blank" class="btn btn-xs btn-warning">Orders</a>| | <a href="' . url('admin/update-status/dropshipper/'.Hashids::encode($order->id)). '"  class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>';
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['name', 'email', 'profile_image', 'action'])
                ->make(true);
        }
        return view('admin.drop-shipper.dropshipper_order');
    }

}
