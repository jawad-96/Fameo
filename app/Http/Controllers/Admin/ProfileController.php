<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\Transaction;
use App\Models\WholesellerWallet;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session, Alert, DB, Image, File;
use App\User;
use App\Admin;
use  Hashids, DataTables;
use App\Models\TaxRate;
use App\Models\CouriersAssignment;
use App\Models\CouriersAssignmentDetail;
class ProfileController extends Controller

{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()

    {
        $profile = Auth::user();
        return view('admin.profile.index', compact('profile'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {

        $id = Auth::id();
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $requestData = $request->all();

        $user = Admin::findOrFail($id);
        $user->update($requestData);

        Session::flash('success', 'Profile updated!');
        return redirect('admin/profile');
    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function changePasswordView()
    {

        $data = Admin::find(Auth::id());
        return view('admin.profile.change-password', compact('data'));
    }


    /**
     * Change password.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function changePassword(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $requestData = $request->all();
        $validator->after(function($validator) use ($request) {
            $user = Auth::user();

            //checking the old password first
            $check  = Auth::attempt([
                'email' => $user->email,
                'password' => $request->current_password
            ]);

            if(!$check) {
                $validator->errors()->add('current_password','Your current password is incorrect, please try again.');
            }
        });


        if ($validator->fails())
            return redirect('admin/change-password')->withErrors($validator);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        Session::flash('success', 'Password updated!');
        return redirect('admin/change-password');

    }
    public function getInvoiceList1(Request $request)
    {
        $users = User::whereIn('type',['dropshipper','wholesaler']);
        if ($request->ajax()) {

            if(!empty($request->user_id)){
                $users->where('id',$request->user_id);
            }




            return Datatables::of($users->get())
                ->addColumn('orders_details', function ($order) {
                    return '<span class="details-control"></span>';
                })
                ->addColumn('sum_amount', function ($order) use($request) {
                    $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return  $this->getProduct($transactions->get(),'item_amount');

                    }

                    return  $this->getProduct($transactions->get(),'item_amount');
                })->addColumn('courier_vat', function ($order) use($request) {
                     $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return  $this->getProduct($transactions->get(),'courier_vat');

                    }

                    return  $this->getProduct($transactions->get(),'courier_vat');
                })
                
                ->addColumn('product_vat', function ($order) use($request) {
                     $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return  $this->getProduct($transactions->get(),'product_vat');

                    }

                    return  $this->getProduct($transactions->get(),'product_vat');
                })
                ->addColumn('courier_amount', function ($order) use($request) {
                     $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return  $this->getProduct($transactions->get(),'courier_amount');

                    }

                    return  $this->getProduct($transactions->get(),'courier_amount');
                })
                 ->addColumn('total_product_amount', function ($order) use($request) {
                     $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return  '<b>'.$this->getProduct($transactions->get(),'total_product_amount').'</b>';

                    }

                    return  '<b>'.$this->getProduct($transactions->get(),'total_product_amount').'</b>';
                })
                  ->addColumn('total_vat_amount', function ($order) use($request) {
                     $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return  '<b>'.$this->getProduct($transactions->get(),'total_vat_amount').'</b>';

                    }

                    return '<b>'. $this->getProduct($transactions->get(),'total_vat_amount').'</b>';
                }) ->addColumn('grand_total', function ($order) use($request) {
                     $transactions=  Transaction::with('cart')->where('user_id',$order->id);
                     
                    if(!empty($request->start_date) and !empty($request->end_date)){

                        $startDate = $request->start_date;
                        $endDate = $request->end_date;

                       $transactions = $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);
                       return '<b>'. $this->getProduct($transactions->get(),'grand_total').'</b>';

                    }

                    return  '<b>'.$this->getProduct($transactions->get(),'grand_total').'</b>';
                })
                ->addColumn('wallet_amount',function($order){
                    $data = WholesellerWallet::whereUserId($order->id)->get();

                    return  '£'. round(($data->sum('credit') - $data->sum('debit')),2);

                })  ->rawColumns(['grand_total','total_vat_amount','total_product_amount'])
                ->make(true);
        }
        $data = $users->get();

        return view('admin.profile.user_invoice',compact('data'));


    }
    
    public function getInvoiceList(Request $request)
    {
        
//        if ($request->filled('abcd')) {
//        $sc = ShoppingCart::find(126);
//        $cart_details = unserialize($sc->cart_details);
//        $cart_details[0]['id'] = 548;
//        $cart_details[0]['name'] = '1 × Round Plastic Plant Pot (Orange)';
//        $cart_details[0]['price'] = 2.10;
//        $cart_details = serialize($cart_details);
//        $ac = ShoppingCart::find(121);
//        $ac->update(['cart_details' => $cart_details]);
//        dd($cart_details); 
//        }
        
        if ($request->ajax()) {
            
            $userId = (@$request->user_id) ? $request->user_id : 0;
            
            $transactions = Transaction::with('cart', 'user')->where('user_id', $userId);
            
            if(!empty($request->start_date) and !empty($request->end_date)){

                $startDate = $request->start_date;
                $endDate = $request->end_date;

               $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);

            }
            
            return Datatables::of($transactions)
                ->addColumn('orders_details', function ($order) {
                    return '<span class="details-control"></span>';
                })
                ->addColumn('date', function ($transaction) {
                    return  date('d-m-Y h:i a', strtotime($transaction->created_at));
                })
                ->addColumn('paypal_id', function ($transaction) {
                    return  '<a href="orders/' . Hashids::encode($transaction->id) . '" style="text-decoration:underline;color:red;" target="_blank" >' . $transaction->paypal_id . '</a>';
                })
                ->addColumn('sum_amount', function ($transaction) {
                    return  $this->getProduct($transaction, 'item_amount');
                })
                ->addColumn('courier_vat', function ($transaction){
                     return  $this->getProduct($transaction, 'courier_vat');
                })
                
                ->addColumn('product_vat', function ($transaction){
                    return  $this->getProduct($transaction,'product_vat');
                })
                ->addColumn('courier_amount', function ($transaction) {
                     
                    return  $this->getProduct($transaction, 'courier_amount');
                })
                ->addColumn('total_product_amount', function ($transaction) {
                     
                     return  $this->getProduct($transaction, 'total_product_amount');
                })
                ->addColumn('total_vat_amount', function ($transaction) {
                     return $this->getProduct($transaction, 'total_vat_amount');
                }) 
                ->addColumn('grand_total', function ($transaction) {
                    return $transaction->amount;
                    //return  $this->getProduct($transaction, 'grand_total');
                })
                ->addColumn('grand_total_hidden', function ($transaction) {
                    if ($transaction->is_refunded == 1){
                        return 0;
                    }
                    return $transaction->amount;
                    //return  $this->getProduct($transaction, 'grand_total_hidden');
                })
                ->addColumn('tran_status', function ($transaction) {
                    $status = '';
                    if ($transaction->is_refunded == 1) {
                        $status = '<a href="javascript:void(0)" class="badge bg-success" >Refunded</a>';
                    }
                    return  $status;
                })
                ->rawColumns(['grand_total', 'total_vat_amount', 'total_product_amount', 'tran_status', 'paypal_id'])
                ->make(true);
        }
        
        $data = User::whereIn('type',['dropshipper','wholesaler'])->get();
        

        return view('admin.profile.user_invoice', compact('data'));


    }
    
    public function getUserStatment(Request $request)
    {
        
        if ($request->ajax()) {
            
            $users = collect([]);
            $userId = (@$request->user_id) ? $request->user_id : 0;
            $transactions = Transaction::with('cart', 'user')->where('user_id', $userId);
            
            if(!empty($request->start_date) and !empty($request->end_date)){

                $startDate = $request->start_date;
                $endDate = $request->end_date;

                $transactions->whereDate('created_at','>=',$startDate)->whereDate('created_at','<=',$endDate);

                $transactions = $transactions->get();

            
                $sum_amount = 0;
                $courier_vat = 0;
                $product_vat = 0;
                $courier_amount = 0;
                $total_product_amount = 0;
                $total_vat_amount = 0;
                $grand_total = 0;
                foreach ($transactions as $transaction) {
                    if ($transaction->is_refunded != 1) {
                        $sum_amount = $sum_amount + $this->getProduct($transaction, 'item_amount');
                        $courier_vat = $courier_vat + $this->getProduct($transaction, 'courier_vat');
                        $product_vat = $product_vat + $transaction->tax;
                        $courier_amount = $courier_amount + $this->getProduct($transaction, 'courier_amount');
                        $total_product_amount = $total_product_amount + $this->getProduct($transaction, 'total_product_amount');
                        $total_vat_amount = $total_vat_amount + $this->getProduct($transaction, 'total_vat_amount');
                        $grand_total = $grand_total + $transaction->amount;
                    }
                }

                $users->push(['invoice_number' => 'INV-' . date('M-m-Y', strtotime($startDate)) . '-' . @$transaction->user->id,
                    'date' => date('m/d/Y', strtotime($startDate)) . '-' . date('m/d/Y', strtotime($endDate)),
                    'sum_amount'         => $sum_amount,
                    'courier_vat'       => $courier_vat,
                    'product_vat'      => $product_vat,
                    'courier_amount' => $courier_amount,
                    'total_product_amount' => $total_product_amount,
                    'total_vat_amount' => $total_vat_amount,
                    'total_vat_amount' => $total_vat_amount,
                    'grand_total' => $grand_total,
                    'customer_name' => @$transaction->user->first_name . ' ' . @$transaction->user->last_name,
                    'company_name' => @$transaction->user->company_name,
                    'phone' => @$transaction->user->phone,
                    'email' => @$transaction->user->email,
                    'address' => @$transaction->user->address,
                ]);
            }
            

            return Datatables::of($users)->make(true);
        }
        
        $data = User::whereIn('type', ['dropshipper','wholesaler'])->get();
        

        return view('admin.profile.user_statment', compact('data'));


    }

    public function getProduct($value,$type)
    {
        
        $subtotal = 0;
        $courier=0;
        $courierAmout =0;
        $prudctVat =0;
        $originalPrice =0;
        $courierVat=0;
        $productTotal =0;
        $totalCourier =0;
        if($value){
            $vatCharges = TaxRate::select('rate')->where('id', 1)->first();
            $vatCharges=(int)$vatCharges->rate;
            // foreach($data as $value){

            $courier_assign = CouriersAssignment::where('cart_id', $value->cart_id)->first();
            $cart_details = @unserialize($value->cart->cart_details);
               
               if ($cart_details !== false) {
                foreach ($cart_details as $cart) {

                    $courier = $cart['conditions']->getValue() ?? 0;
                    $courierAmout = ($courier + $courierAmout);
                    $unit_price = $cart['price'];

                    // if(@$courier_assign->status == 2) {
                    // $courierAssignmentDetail = CouriersAssignmentDetail::with('couriers')->where('product_id', $cart['id'])->where('cart_id', $value->cart->id)->first(); 
                    // $courierAmout= $courierAssignmentDetail->couriers->charges;
                    // }    



                    $item_sub_total = $unit_price * $cart['quantity'];
                    $subtotal = ($subtotal + $item_sub_total);
                    $item_discount = (@$cart['item_discount']) ? $cart['item_discount'] : 0;
                    $productDetails = getProductDetails($cart['id']);

                    $courierVat = number_format($courierAmout * ($vatCharges / 100), 2);
                    $totalCourier += ($courierVat + $courierAmout);
                    $prudctVat = $value->tax;

                    $productTotal = ($subtotal + $prudctVat);
                }

                if (@$courier_assign->status == 2) {
                    $cartContents = $this->attach_shipment_charges($cart_details, $value->cart_id);
                    $courierAmout = $cartContents['shipment_charges'];

                    $courierVat = number_format($courierAmout * ($vatCharges / 100), 2);
                    $totalCourier = ($courierVat + $courierAmout);
                }
                
            }

            if ($value->is_refunded == 1 && $type == 'grand_total_hidden') {
                $totalCourier = 0;
                $productTotal = 0;
            }  
                               
           //}
           switch($type){
               case 'item_amount':
                   return number_format($subtotal, 2);
               case 'product_vat':
                  return number_format($prudctVat, 2);
               case 'courier_amount':
                 return  number_format($courierAmout, 2);
                case 'courier_vat':
                    return  number_format($courierVat, 2);
               case 'total_product_amount';
                  return number_format($productTotal, 2);
                  case 'total_vat_amount':
                      return number_format($totalCourier, 2);
               
                   default :
                       return round(($totalCourier + $productTotal), 2);
           }
          
        }else{
            return  '£0';
        }
    }

    public function attach_shipment_charges($cartContents, $cartId)
    {
        $courier_id = 0;
        $temp = 0;
        $shipment_charges = 0;
        $pre_key = 0;

        foreach ($cartContents as $key => $item) {
            $courierAssignmentDetail = CouriersAssignmentDetail::where('product_id', $item['id'])->where('cart_id', $cartId)->first();

            if ($courier_id == 0) {
                $courier_id = @$courierAssignmentDetail->group_no;
                if (@$courierAssignmentDetail->couriers) {
                    $shipment_charges = $shipment_charges + $courierAssignmentDetail->couriers->charges;
                    $cartContents[$key]['charges_check'] = 2;
                }
            }

            if ($courier_id != @$courierAssignmentDetail->group_no) {
                $cartContents[$pre_key]['charges_check'] = 1;
                $courier_id = $courierAssignmentDetail->group_no;
                $shipment_charges = $shipment_charges + $courierAssignmentDetail->couriers->charges;
            }

            $pre_key = $key;
        }

        $cartContents[$pre_key]['charges_check'] = 1;
        $cartContents['shipment_charges'] = $shipment_charges;

        return $cartContents;
    }

    public function getProduct1($data,$type)
    {
        $subtotal = 0;
        $courier=0;
        $courierAmout =0;
        $prudctVat =0;
        $originalPrice =0;
        $courierVat=0;
        $productTotal =0;
        $totalCourier =0;
        if($data->isNotEmpty() ){
             $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;
           foreach($data as $value){
  
        $courier_assign = CouriersAssignment::where('cart_id',$value->cart->id)->first();
               $cart_details = unserialize($value->cart->cart_details);
                foreach($cart_details as $cart){
                    
                     
                  
                     $courier = $cart['conditions']->getValue()??0;
                                 $courierAmout = ($courier + $courierAmout );
                                $unit_price = $cart['price'];
                                
                    if(@$courier_assign->status == 2) {
                        $courierAssignmentDetail = CouriersAssignmentDetail::with('couriers')->where('product_id', '=', $cart['id'])->where('cart_id', $value->cart->id)->first();
                         
                  $courierAmout= $courierAssignmentDetail->couriers->charges;
                }    

                                $item_sub_total = $unit_price * $cart['quantity'];
                                $subtotal = ($subtotal + $item_sub_total);
                                $item_discount = (@$cart['item_discount'])?$cart['item_discount']:0;
                                
                                  $productDetails = getProductDetails($cart['id']);
            
                 $courierVat+= number_format($courierAmout * ($vatCharges/100),2);
                $totalCourier +=($courierVat+$courierAmout);
                $prudctVat += $value->tax; 
         
                $productTotal +=($subtotal+$prudctVat);
                    
                }
              
                               
           }
           switch($type){
               case 'item_amount':
                   return '£'.$subtotal;
               case 'product_vat':
                  return '£'.$prudctVat;
               case 'courier_amount':
                 return  '£'.$courierAmout;
                case 'courier_vat':
                    return  '£'.$courierVat;
               case 'total_product_amount';
                  return '£'.$productTotal;
                  case 'total_vat_amount':
                      return '£'.$totalCourier;
               
                   default :
                       return '£'. ($totalCourier + $productTotal);
           }
          
        }else{
            return  '£0';
        }
    }

}

