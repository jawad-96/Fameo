<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\WholesellerWallet;
use App\Models\WholesellerPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\User;
use Illuminate\Http\Request;
use Session,Hashids,DataTables,DB;

class WholesalerController extends Controller
{

   public function __construct()
   {
        $this->middleware('permission:view wholesaler', ['only' => ['index']]);
        $this->middleware('permission:add wholesaler', ['only' => ['create','store']]);
        $this->middleware('permission:edit wholesaler', ['only' => ['edit','update']]);
        $this->middleware('permission:delete wholesaler', ['only' => ['destroy']]);
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        if($request->ajax()){

            $users = User::where('type','wholesaler')->orderBy('updated_at','desc');

            return DataTables::of($users)
                ->addColumn('is_active', function ($user) {
                 $satatus = '<a class="btn btn-xs btn-danger">Inactive</a>';
                 if($user->is_active=="yes")
                    $satatus = '<a class="btn btn-xs btn-success">Active</a>';

                    return $satatus;
                })
                ->addColumn('name', function ($user) {
                  return $user->full_name;
                })
                ->addColumn('wallet_amount', function ($user) {
                    return number_format(getWholsellerDataWallet($user->id),'2','.','');
                })
                ->addColumn('payment_amount', function ($user) {
                    return '<a class="text-primary" href="wholesaler/payments/'. Hashids::encode($user->id).'" >'.number_format(getWholsellerPaymentAmount($user->id),'2','.','').'</a>';
                })
                ->addColumn('action', function ($user) {
                $action = '';
                if(Auth::user()->can('edit wholesaler'))
                    $action .= '<a href="wholesaler/'. Hashids::encode($user->id).'/edit" class="text-primary" data-toggle="tooltip" title="Edit Wholesaler"><i class="fa fa-lg fa-edit"></i></a>';
                if(Auth::user()->can('delete wholesaler'))
                    $action .= '<a href="wholesaler/'.Hashids::encode($user->id).'" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Wholesaler"><i class="fa fa-lg fa-trash"></i></a>';

                   $action .= '<a href="javascript:void(0)" class="text-primary walletAdd"  data-id="'.$user->id.'" data-toggle="tooltip" title="Add Wallet Amount"><i class="fa fa-google-wallet"></i></a>';
                   
                   $action .= '<a href="javascript:void(0)" class="text-success receivePayment"  data-id="'.$user->id.'" data-toggle="tooltip" title="Receive Payment"><i class="fa fa-download"></i></a>';
                         $action .= '<a href="drop-shipper/payments/'. Hashids::encode($user->id).'" class="text-danger" data-toggle="tooltip" title="Payment History"><i class="fa fa-history"></i></a>';

                return $action;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['is_active','payment_amount','action'])
                ->make(true);

        }

        return view('admin.wholesaler.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.wholesaler.create');
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
       $requestData['type'] = 'wholesaler';

       $user = User::create($requestData);

        Session::flash('success', 'Wholesaler added!');

        return redirect('admin/wholesaler');
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

        return view('admin.wholesaler.edit', compact('user'));
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

        $requestData['type'] = 'wholesaler';

        $user = User::findOrFail($id);
        $user->update($requestData);

        Session::flash('success', 'Wholesaler updated!');

        return redirect('admin/wholesaler');
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
            $response['message'] = 'Wholesaler deleted!';
            $status = $this->successStatus;
        }else{
            $response['message'] = 'Wholesaler not exist against this id!';
            $status = $this->errorStatus;
        }

        return response()->json(['result'=>$response], $status);

    }

    public function wholeSalerOrders()
    {
        if(\request()->ajax()) {
            return Datatables::of(User::where('type', 'wholesaler')->orderBy('updated_at','desc'))
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
                    return '<img width="30" src="' . checkImage('customers/thumbs/' . $customer->profile_image) . '" />';
                })
                ->addColumn('action', function ($order) {
                    return '<a href="orders?user_id=' . Hashids::encode($order->id) . '" target="_blank" class="btn btn-xs btn-warning">Orders</a> | <a href="' . url('admin/update-status/wholesaler/'.Hashids::encode($order->id)). '"  class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>';
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['profile_image', 'action','name','email'])
                ->make(true);
        }
        return view('admin.wholesaler.whole_saler_orders');
    }
    
    public function wholesalerPayments($id)
    {
        if(\request()->ajax()) {
            $userId = decodeId($id);
            $payments = WholesellerPayment::where('user_id', $userId);

            if(!empty(\request()->from_date)){
                $payments->where('date', '>=' , date('Y-m-d', strtotime(\request()->from_date)));
            }

            if(!empty(\request()->to_date)){
                $payments->where('date', '<=' , date('Y-m-d', strtotime(\request()->to_date)));
            }

            $payments = $payments->orderBy('date','desc');

            return Datatables::of($payments)
                ->addColumn('date', function ($payment) {
                    return date('d-m-Y', strtotime($payment->date));
                })   
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['profile_image', 'action','name','email'])
                ->make(true);
        }
        return view('admin.wholesaler.whole_saler_payments', compact('id'));
    }

    public function addWalletAmount()
    {
        $wholeSelletWallet = WholesellerWallet::create([
            'credit' => \request()->amount,
            'user_id' => \request()->id,
            'payment_mode' => 'wallet',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return ['status' => true, 'message' => 'Successfully Inserted'];
    }
    
    public function addWholesellerPaymentAmount(Request $request)
    {
        $payment = WholesellerPayment::create([
            'amount' => $request->amount,
            'user_id' => $request->id,
            'date' => date('y-m-d', strtotime($request->date)),
            'payment_mode' => @$request->payment_mode,
            'note' => @$request->note
        ]);

        if ($payment) {
            WholesellerWallet::create([
                'credit' => $request->amount,
                'user_id' => $request->id,
                'date' => date('y-m-d', strtotime($request->date)),
                'payment_id' => $payment->id,
                'payment_mode' => @$request->payment_mode,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        

        return ['status' => true, 'message' => 'Successfully Inserted'];
    }

}
