<?php

namespace App\Http\Controllers;


use App\Models\Courier;
use App\Models\CouriersAssignment;
use App\Models\CouriersAssignmentDetail;
use App\Models\Email;
use App\Models\Newsletter_subscriber;
use App\Models\ShoppingCart;
use App\Models\TaxRate;
use App\Models\Transaction;
use App\Models\WholesellerWallet;
use App\User;
use Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Auth;
use App\Models\ShoppingCartHistory;
use Carbon\Carbon;
use Session;
use Log;

class CartController extends Controller
{
    public function cart()
    {
        return view('cart.cart');
    }

    /*add to cart*/
    public function add(Request $request)
    {

        // add item to cart
        if(Auth::id()){
            $product = Product::with('shipping')->find($request->id);
          

            // if product has variant
            if($product->product_id != 0 ){
                $data = getDefaultVariant($product->product_id);
                $product->shipping_id = $data->shipping_id;
                
                productDefaultCourier($request->id, $data->shipping_id);
            }
          
            // set quantity
            $qty = ($request->quantity)??1;

            // add shipping charges condition

            $shipping = new \Darryldecode\Cart\CartCondition(array(
                'name' => addShippingCharges($product,Auth::user()->type)['name'],
                'type' => 'shipping',
                'target' => 'total',
                'value' => addShippingCharges($product,Auth::user()->type)['charges'],
                'attributes' =>  ['courier_id' => $product->shipping_id]
            ));
            $productData = array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->discountedPrice,
                'quantity' => $qty,
                'attributes' => array(),
                'conditions' => $shipping
            );

            $cart = ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();

            if(Auth::user()->type != 'dropshipper' || !@$cart->courierAssignment || @$cart->courierAssignment->status == 3|| @$cart->courierAssignment->status == 4  )
            {
                Cart::session(Auth::id())->add($productData);
                $cartTotal = Cart::session(Auth::id())->getTotalQuantity();
                $cartPrice = Cart::session(Auth::id())->getSubTotal();
                $cartPrice = number_format($cartPrice,2);
                $cart = Cart::session(Auth::id())->getContent()->values()->toArray();
         
             
                $this-> updateCartInDB($cart);
            }
            else
            {
                return ['status' => false,'message' => 'Sorry Your Previous Request Already In Process'];
            }




        }else{
            /*Cart::add($productData);
            $cartTotal = Cart::getTotalQuantity();
            $cartPrice = Cart::getSubTotal();*/
            return ['status' => false,'message' => 'Please login first to add item in your cart.'];
        }

        return ['status' => true,'cartTotal' => $cartTotal, 'cartPrice' => $cartPrice ,'message' => ''.$product->name.' added successfully.'];
    }

    public function cartDetails()
    {
        

        $count          = (Auth::id())?Cart::session(Auth::id())->getContent()->count():Cart::getContent()->count();
        $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();
        
        if (Auth::user()->type == 'dropshipper'){
            $subTotal=0;
        }else{
            $subTotal       = (Auth::id())?Cart::session(Auth::id())->getSubTotal():Cart::getSubTotal();
        }
      
        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();

        if(!$cart){
            return back()->with('error','No data found');
        }

        $courier_assign = CouriersAssignment::where('cart_id',$cart->id)->first();

        $cartSum = 0;
        $originalPrice = 0;
        $woriginalPrice = 0;
        $wsubtotal = 0;
        $total_shipment_charges = 0;
        
        foreach($cartContents as $item){

            

            if (Auth::user()->type != 'retailer') {
                $subTotal += $item->getPriceSum();
            }
           
            $productDetails = getProductDetails($item->id);
            if($productDetails) {
                $originalPrice += ($productDetails->price * $item->quantity);
            }

            if (Auth::user()->type == 'dropshipper') {
                
                if (@$courier_assign && $courier_assign->status == 2) {
                    $courierAssignmentDetail = CouriersAssignmentDetail::where('product_id', '=', $item->id)->where('cart_id', $cart->id)->first();
                    $item->courier_detail = $courierAssignmentDetail;
                       
                   
                    $item->courier_id = $courierAssignmentDetail->couriers->id??'';
                } else if ($count == 1 && $item->quantity == 1) {
                    
                    $total_shipment_charges = @$productDetails->courier->charges;
                 
                    $item->courier_id = @$productDetails->courier->id;
                    $subTotal = $subTotal+$total_shipment_charges;
                }
            } elseif (Auth::user()->type == 'wholesaler') {

                $this->applyDiscount($cartContents);
                $originalPrice = $cartContents->orignalPrice;
                $subTotal = $cartContents->subTotal;
                

            }

        }

        if(Auth::user()->type == 'dropshipper' && @$courier_assign->status == 2 ) {
 
            $cartContents = $cartContents->sortBy('courier_id');
            $this->attach_color($cartContents);
            $this->attach_shipment_charges($cartContents);
            $total_shipment_charges=$cartContents->shipment_charges;
            $subTotal = $subTotal+$total_shipment_charges;
           

        }

        if(Auth::user()->type == 'wholesaler' && Auth::user()->type == 'dropshipper'  ) {
               
            $cartContents = $courier_assign->sortBy('courier_id');
            $this->attach_color($cartContents);
            $this->attach_shipment_charges($cartContents);
            $total_shipment_charges=$cartContents->shipment_charges;

        }



        $couriers= Courier::all();
        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;
        
        //$subTotal = number_format($subTotal,2);
                // dd($subTotal);

//        dd($vatCharges);


        // if(Auth::id()==4) {
        //     dump($cartContents);
        //     dd($total_shipment_charges);
        // }

        return view('cart.cartDetails', compact('vatCharges','couriers','total_shipment_charges','cart','count', 'cartContents', 'subTotal', 'cartSum', 'originalPrice'));
    }

    public function cartDetails1($id)
    {
        $count          = (Auth::id())?Cart::session(Auth::id())->getContent()->count():Cart::getContent()->count();
        $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();
        $subTotal       = (Auth::id())?Cart::session(Auth::id())->getSubTotal():Cart::getSubTotal();
        $subTotal = number_format($subTotal,2);
        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();


        $courier_assign = CouriersAssignment::where('cart_id',$cart->id)->first();
        $cartSum = 0;
        $originalPrice = 0;
        $woriginalPrice = 0;
        $wsubtotal = 0;
        $total_shipment_charges = 0;
        
        foreach($cartContents as $item){

            $cartSum += $item->getPriceSum();
            $productDetails = getProductDetails($item->id);
            if($productDetails) {
                $originalPrice += ($productDetails->price * $item->quantity);
            }

                if (Auth::user()->type == 'dropshipper') {
                    if ($count == 1 && $item->quantity == 1) {
                        $total_shipment_charges = @$productDetails->courier->charges;
                        $item->courier_id = @$productDetails->courier->id;
                    }
                    if (Auth::user()->type == 'dropshipper' && @$courier_assign->status == 2) {
                        $courierAssignmentDetail = CouriersAssignmentDetail::where('product_id', '=', $item->id)->where('cart_id', $cart->id)->first();
                        $item->courier_detail = $courierAssignmentDetail;
                        $item->courier_id = $courierAssignmentDetail->couriers->id;
                    }
                } elseif (Auth::user()->type == 'wholesaler') {

                    $this->applyDiscount($cartContents);
                    $originalPrice = $cartContents->orignalPrice;
                    $subTotal = $cartContents->subTotal;

                }

        }

        if(Auth::user()->type == 'dropshipper' && @$courier_assign->status == 2 ) {

            $cartContents = $cartContents->sortBy('courier_id');
            $this->attach_color($cartContents);
            $this->attach_shipment_charges($cartContents);
            $total_shipment_charges=$cartContents->shipment_charges;

        }

        if(Auth::user()->type == 'wholesaler' && Auth::user()->type == 'dropshipper'  ) {

            $cartContents = $cartContents->sortBy('courier_id');
            $this->attach_color($cartContents);
            $this->attach_shipment_charges($cartContents);
            $total_shipment_charges=$cartContents->shipment_charges;

        }



       $couriers= Courier::all();
        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;

//        dd($vatCharges);


//        dd($cartContents,$subTotal);

        return view('cart.cartDetails', compact('vatCharges','couriers','total_shipment_charges','cart','count', 'cartContents', 'subTotal', 'cartSum', 'originalPrice'));
    }

    public function applyDiscount($cartContents,$originalPrice=0,$subTotal=0)

    {

        $woriginalPrice=0;
        $wsubtotal=0;
        $temp=0;

        foreach($cartContents as $item){

            $productDetails = getProductDetails($item->id);

            if(Auth::user()->type == 'wholesaler')
            {
                $user= Auth::user();
                $productPercentaget = ($productDetails->cost * Auth::user()->mark_up/100);
                $woriginalPrice  += (($productDetails->cost + $productPercentaget) * $item->quantity);

                $item->price=($productDetails->cost + $productPercentaget);
                $item->cprice= ($productDetails->cost + $productPercentaget);

                if($item->quantity >= $user->quantity_1 && $user->quantity_1  >0 && $user->percentage_1 > 0 )
                {

                    $item->price =number_format(($item->cprice*(100-$user->percentage_1))/100,2);

                } if($item->quantity >= $user->quantity_2 && $user->quantity_2  >0 &&  $user->percentage_2 > 0 )
                {

                    $item->price =number_format(($item->cprice*(100-$user->percentage_2))/100,2);

//                    dd($item->price,$user->percentage_2);

                }
                if($item->quantity >= $user->quantity_3 && $user->quantity_3  >0 && $user->percentage_3 > 0 )
                {

                    $item->price =number_format(($item->cprice*(100-$user->percentage_3))/100,2);

                }

                $wsubtotal+=($item->price*$item->quantity);


            }

        }

        $cartContents->orignalPrice=$woriginalPrice;
        $cartContents->subTotal=$wsubtotal;
//    dd($woriginalPrice,$wsubtotal);

    }
    public function attach_color($cartContents)

    {
        $color= array('lightyellow','lightblue','lightpink','lightcoral','lightsalmon','lightgreen','lightcyan','lightseagreen','lightskyblue','light','lightyellow','lightyellow','lightblue','lightpink','lightcoral','lightsalmon','lightgreen','lightcyan','lightseagreen','lightskyblue','light','lightyellow','lightyellow','lightblue','lightpink','lightcoral','lightsalmon','lightgreen','lightcyan','lightseagreen','lightskyblue','light','lightyellow','lightyellow','lightblue','lightpink','lightcoral','lightsalmon','lightgreen','lightcyan','lightseagreen','lightskyblue','light','lightyellow','lightyellow','lightblue','lightpink','lightcoral','lightsalmon','lightgreen','lightcyan','lightseagreen','lightskyblue','light',);
        $courier_id=0;
        $temp=0;

        foreach($cartContents as $item){


            if($courier_id == 0)
            {$courier_id= $item->courier_id;}

            if($courier_id == $item->courier_id)
            {
                $item->color=$color[$temp];
            }else
            {
                $temp++;
                $courier_id = $item->courier_id;
                $item->color=$color[$temp];


            }



        }
    }

    public function attach_shipment_charges($cartContents)
    {
        $courier_id=0;
        $temp=0;
        $shipment_charges=0;
        $pre_key=0;
        
        foreach($cartContents as $key=> $item)
        {
            if($courier_id == 0)
            {
                $courier_id= $item->courier_detail->group_no;
                if (@$item->courier_detail->couriers) {
                    $shipment_charges = $shipment_charges + $item->courier_detail->couriers->charges;
                    $cartContents[$key]->charges_check=2;
                }
            }

            if($courier_id != $item->courier_detail->group_no)
            {
                $cartContents[$pre_key]->charges_check=1;
                $courier_id=$item->courier_detail->group_no;
                $shipment_charges = $shipment_charges + $item->courier_detail->couriers->charges;
            }
            
            $pre_key=$key;
        }

        $cartContents[$pre_key]->charges_check=1;
        $cartContents->shipment_charges=$shipment_charges;
    }




    /*public function makePayment()
    {
        $userData       = unserialize(ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first()->user_details);
        $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();
        $subTotal       = (Auth::id())?Cart::session(Auth::id())->getSubTotal():Cart::getSubTotal();
        $count          = (Auth::id())?Cart::session(Auth::id())->getContent()->count():Cart::getContent()->count();
        $subTotal = number_format($subTotal,2);

        User::where('id',Auth::id())->update([
            'updated_at' => Carbon::now()
        ]);

        if(settingValue('wholesaler_quantity')!=""){
            if((Cart::session(Auth::id())->getTotalQuantity() < settingValue('wholesaler_quantity')) && (Auth::user()->type == 'wholesaler')) {
                Session::flash('error', 'Sorry! You must add atleast '.settingValue('wholesaler_quantity').' items in cart.');
                return redirect()->back();
            }
        }
        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();
        $courier_assign = CouriersAssignment::where('cart_id',$cart->id)->first();
        $cartSum = 0;
        $originalPrice = 0;
        $total_shipment_charges = 0;
        $courier= 0;
        foreach($cartContents as $item){

            $cartSum += $item->getPriceSum();
            $productDetails = getProductDetails($item->id);
            $originalPrice  += ($productDetails->price * $item->quantity);


            if(Auth::user()->type == 'dropshipper')
            {
                $productDetail = Courier::where('id',$item->conditions->getAttributes()['courier_id'])->first();
                $item->couriers = $productDetail;
                $subTotal =0;
                $total_shipment_charges =  $total_shipment_charges +$item->conditions->getValue() ;
                $item->courier_id = $item->conditions->getAttributes()['courier_id'];;
                $courier = $courier+$item->conditions->getValue();
                $subTotal = $subTotal+($cartSum + $courier);

                if(Auth::user()->type == 'dropshipper' && @$courier_assign->status == 2 )
                {
                    $courierAssignmentDetail= CouriersAssignmentDetail::where('product_id','=',$item->id)->where('cart_id',$cart->id)->first();
                    $item->courier_detail=$courierAssignmentDetail;
                    $item->courier_id=$courierAssignmentDetail->couriers->id;

                }
            }
            elseif(Auth::user()->type == 'wholesaler')
            {


                $this->applyDiscount($cartContents);

                $originalPrice=$cartContents->orignalPrice;
                $subTotal=$cartContents->subTotal;

            }
            if(empty($item->courier_id)){
                return  back()->with('error','Please assign courier to all products');
            }
        }


        if(Auth::user()->type == 'dropshipper'  ) {

            $cartContents = $cartContents->sortBy('courier_id');

            $this->attach_color($cartContents);
            $this->attach_shipment_charges($cartContents);
           $total_shipment_charges=$cartContents->shipment_charges;

        }

        if(Auth::user()->type == 'dropshipper' && (($count == 1 && $item->quantity  > 1 ) && @$courier_assign->status !=2  ) ) {

            Session::flash('error', 'Kindly Send Request To Admin');
            return redirect()->back();

        }

        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;


        return view('cart.makePayment', compact('vatCharges','total_shipment_charges','userData', 'cartContents', 'subTotal', 'cartSum', 'originalPrice'));
    }*/
    
    
    public function makePayment()
    {
        $userData       = unserialize(ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first()->user_details);
        $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();
        
        $count          = (Auth::id())?Cart::session(Auth::id())->getContent()->count():Cart::getContent()->count();
     
 if (Auth::user()->type == 'dropshipper'){
            $subTotal=0;
        }else{
            $subTotal       = (Auth::id())?Cart::session(Auth::id())->getSubTotal():Cart::getSubTotal();
        }
      
        User::where('id',Auth::id())->update([
            'updated_at' => Carbon::now()
        ]);

        if(settingValue('wholesaler_quantity')!=""){
            if((Cart::session(Auth::id())->getTotalQuantity() < settingValue('wholesaler_quantity')) && (Auth::user()->type == 'wholesaler')) {
                Session::flash('error', 'Sorry! You must add atleast '.settingValue('wholesaler_quantity').' items in cart.');
                return redirect()->back();
            }
        }
        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();
        $courier_assign = CouriersAssignment::where('cart_id',$cart->id)->first();
        $cartSum = 0;
        $originalPrice = 0;
        $total_shipment_charges = 0;
        foreach($cartContents as $item){

            if (Auth::user()->type != 'retailer') {
                $subTotal += $item->getPriceSum();
            }
            $productDetails = getProductDetails($item->id);
            $originalPrice  += ($productDetails->price * $item->quantity);


            if(Auth::user()->type == 'dropshipper')
            {
                
                if(@$courier_assign && $courier_assign->status == 2 )
                {
                    $courierAssignmentDetail= CouriersAssignmentDetail::where('product_id','=',$item->id)->where('cart_id',$cart->id)->first();
                    $item->courier_detail=$courierAssignmentDetail;
                    $item->courier_id=$courierAssignmentDetail->couriers->id;

                } else if($count ==1 && $item->quantity == 1)
                {


                    $total_shipment_charges=@$productDetails->courier->charges;
                    $item->courier_id=@$productDetails->courier->id;
                     $subTotal = $subTotal+$total_shipment_charges;


                }
            }
            elseif(Auth::user()->type == 'wholesaler')
            {


                $this->applyDiscount($cartContents);

                $originalPrice=$cartContents->orignalPrice;
                $subTotal=$cartContents->subTotal;

            }
        }


        if(Auth::user()->type == 'dropshipper' && @$courier_assign->status == 2 ) {

            $cartContents = $cartContents->sortBy('courier_id');
            $this->attach_color($cartContents);
            $this->attach_shipment_charges($cartContents);
            $total_shipment_charges=$cartContents->shipment_charges;
            $subTotal = $subTotal+ $total_shipment_charges;

        }

        if(Auth::user()->type == 'dropshipper' && (($count == 1 && $item->quantity  > 1 ) && @$courier_assign->status !=2  ) ) {

            Session::flash('error', 'Kindly Send Request To Admin');
            return redirect()->back();

        }

        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;

        
        //$subTotal = number_format($subTotal,2);

        return view('cart.makePayment', compact('vatCharges','total_shipment_charges','userData', 'cartContents', 'subTotal', 'cartSum', 'originalPrice'));
    }
 

    public function proceedAdminRequest()
    {
        $userData       = unserialize(ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first()->user_details);
        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();
        $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();
        $subTotal       = (Auth::id())?Cart::session(Auth::id())->getSubTotal():Cart::getSubTotal();
        $subTotal = number_format($subTotal,2);
        $count          = (Auth::id())?Cart::session(Auth::id())->getContent()->count():Cart::getContent()->count();



        if(settingValue('wholesaler_quantity')!="")
        {
            if((Cart::session(Auth::id())->getTotalQuantity() < settingValue('wholesaler_quantity')) && (Auth::user()->type == 'wholesaler')) {
                Session::flash('error', 'Sorry! You must add atleast '.settingValue('wholesaler_quantity').' items in cart.');
                return redirect()->back();
            }
        }

//        if(Auth::user()->type == 'dropshipper' && $count<3 ) {
//
//            Session::flash('error', 'Sorry! You must add atleast 3 items in cart.');
//            return redirect()->back();
//
//        }
        $cartSum = 0;
        $originalPrice = 0;


        $courierAssignment =CouriersAssignment:: where('cart_id',$cart->id)->first();


        if(!$courierAssignment)
        {
            $courierAssignment = new CouriersAssignment();
            $courierAssignment->user_id=auth()->user()->id;
            $courierAssignment->cart_id=$cart->id;

        }
        $courierAssignment->status=1;
        $courierAssignment->save();



        foreach($cartContents as $item){

            $courierAssignmentDetail= CouriersAssignmentDetail::where('product_id','=',$item->id)->where('cart_id',$cart->id)->first();

//            dd($courierAssignmentDetail);

            $cartSum += $item->getPriceSum();
            $productDetails = getProductDetails($item->id);
            $originalPrice  += ($productDetails->price * $item->quantity);


            if(!$courierAssignmentDetail)
            {
                $courierAssignmentDetail = new CouriersAssignmentDetail();
                $courierAssignmentDetail->product_id=$item->id;
                $courierAssignmentDetail->couriers_assignment_id=$courierAssignment->id;
                $courierAssignmentDetail->cart_id= $cart->id;
                $courierAssignmentDetail->quantity=  $item->quantity;
                $courierAssignmentDetail->price=  $productDetails->price;
                $courierAssignmentDetail->save();
            }

        }








        $email = $courierAssignment->user->email;
       // $email = "baadrayltd@gmail.com";

        $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => $email,
            'email_subject' => 'New Request For Courier Assignmnet',
            'user_name'     => 'User',
            'final_content' => '<p><b>Dear Admin</b></p>
                                    <p>New Request For Courier Assignmnet</p>',
        ];
        try{
            Email::sendEmail($data);
        }
        catch(Exception $e)
        {
            Log::error('Order Email error: ' . $e->getMessage());
        }

        $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => 'aqsinternational@badrayltd.co.uk',           //'info@badrayltd.co.uk',
            'email_subject' => 'New Request For Courier Assignmnet',
            'user_name'     => 'User',
            'final_content' => '<p><b>Dear Admin</b></p>
                                    <p>New Request For Courier Assignmnet</p>',
        ];
        try{
            Email::sendEmail($data);
        }
        catch(Exception $e)
        {
            Log::error('Order Email error: ' . $e->getMessage());
        }

        $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => 'office@badrayltd.co.uk',
            'email_subject' => 'New Request For Courier Assignmnet',
            'user_name'     => 'User',
            'final_content' => '<p><b>Dear Admin</b></p>
                                    <p>New Request For Courier Assignmnet</p>',
        ];
        try{
            Email::sendEmail($data);
        }
        catch(Exception $e)
        {
            Log::error('Order Email error: ' . $e->getMessage());
        }

//        dd('ok');


        Session::flash('success', 'Your order has been sent to admin');

        return redirect('cart-checkout');
    }



    public function cancelRequestCustomer($id)
    {

        $courierAssignment =CouriersAssignment:: find($id);
        $courierAssignment->status = 4;
        $courierAssignment->cart_id = 0;
        $courierAssignment->save();


        CouriersAssignmentDetail::where('couriers_assignment_id',$id)->update(['cart_id'=>0]);


        $email = $courierAssignment->user->email;
//        $email ="mobeen7asif@gmail.com";

        $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => $email,
            'email_subject' => 'Order Cancelation Alert',
            'user_name'     => 'User',
            'final_content' => '<p><b>Dear '.$courierAssignment->user->first_name.'  </b></p>
                                    <p>Your Order has been canceled</p>',
        ];


        try{
            Email::sendEmail($data);
        }
        catch(Exception $e)
        {
            Log::error('Order Cancelation Email error: ' . $e->getMessage());
        }

//        $mail_content = [
//            'name'          => "kashif",
//            // 'content'       => $request->input('message'),
//            'data'       => "hello",
//
//        ];
//
//       // $data = array('name'=>"Stock",'message'=> serialize("hellooooo"));
//        $emails = ['mobeen7asif@gmail.com'];
//
//        Mail::send(['html'=>'mail'], $mail_content, function($message) use($emails) {
//
//            // dd($message);
//
//            $message->to($emails)->subject
//            (' cancel order');
//            $message->from('support@aqsinternationalstore.co.uk','cancel Product');
//        });

        Session::flash('success', 'Your order has been Canceled');
        return redirect('cart-checkout');
    }




    public function clear()
    {
//        make cart empty
        (Auth::id())?Cart::session(Auth::id())->clear():Cart::clear();

//        make db empty
        ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->delete();

        return ['status' => true,'cartTotal' => 0, 'cartPrice' => 0 ,'message' => 'Cart items removed successfully'];
    }

    public function update(Request $request)
    {


        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();

        if(Auth::user()->type != 'dropshipper' || !@$cart->courierAssignment || @$cart->courierAssignment->status == 3|| @$cart->courierAssignment->status == 4  )
        {
        if($request->id && $request->quantity) {
            if (Auth::id()) {
                Cart::session(Auth::id())->update($request->id, array(
                    'quantity' => array(
                        'relative' => false,
                        'value' => $request->quantity
                    ),
                ));
                
                $cartTotal = Cart::session(Auth::id())->getTotalQuantity();
            
                $cartPrice = Cart::session(Auth::id())->getSubTotal();
                $cartPrice = number_format($cartPrice,2);

                $cart = Cart::session(Auth::id())->getContent()->values()->toArray();
                $this->updateCartInDB($cart);
            } else {
                Cart::update($request->id, array(
                    'quantity' => array(
                        'relative' => false,
                        'value' => $request->quantity
                    ),
                ));

                $cart = Cart::getContent()->values()->toArray();
                $cartTotal = Cart::getTotalQuantity();
                $cartPrice = Cart::getSubTotal();

                $cartPrice = number_format($cartPrice,2);

            }

        }else
        {
            return ['status' => false,'message' => 'Sorry Your Previous Request Already In Process'];
        }

            return ['status' => true,'cartTotal' => $cartTotal, 'cartPrice' => $cartPrice ,'message' => 'Cart updated successfully.'];
        }
    }

    public function remove(Request $request)
    {
        $cart=ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();

        if(Auth::user()->type != 'dropshipper' || !@$cart->courierAssignment || @$cart->courierAssignment->status == 3|| @$cart->courierAssignment->status == 4  ) {

            if (Auth::id()) {
                Cart::session(Auth::id())->remove($request->id);
                $cartTotal = Cart::session(Auth::id())->getTotalQuantity();
                $cartPrice = Cart::session(Auth::id())->getSubTotal();

                $cartPrice = number_format($cartPrice,2);




                $cart = Cart::session(Auth::id())->getContent()->values()->toArray();
                $this->updateCartInDB($cart);
            } else {
                Cart::remove($request->id);
                $cartTotal = Cart::getTotalQuantity();
                $cartPrice = Cart::getSubTotal();


                $cartPrice = number_format($cartPrice,2);
            }
        }
        else
        {
            return ['status' => false,'message' => 'Sorry Your Previous Request Already In Process'];


        }

        return ['status' => true,'cartTotal' => $cartTotal, 'cartPrice' => $cartPrice ,'message' => 'Item removed successfully.'];
    }

    public function CompletePayment(Request $request)
    {
        $data = ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();

        if($data){
            $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();

            $tax        = 0;
            $cost       = 0;
            $discount   = 0;
            foreach($cartContents as $row){
                $product = Product::with('tax_rate')->findOrFail($row->id);

                $qty        = $row->quantity;
                $tax        = ($tax + (($product->tax_rate->rate / 100) * $product->price) * $qty);
                $cost       = ($cost + ($product->cost * $qty));
                $discount   = $discount + (($product->price - $product->discountedPrice) * $qty);

                $totalQty   = (Auth::id())?Cart::session(Auth::id())->getTotalQuantity():Cart::getTotalQuantity();
            }
            $this->updateUserStatus();
//            make transaction entry
            $transaction['user_id']   = Auth::id();
            $transaction['cart_id']   = $data->id;
            $transaction['qty']       = $totalQty;
            $transaction['cost']      = $cost;
            $transaction['discount']  = $request->discount;
            $transaction['tax']       = $request->vat_amount;
            $transaction['paypal_id'] = $request->trans_id;
            $transaction['amount']    = $request->amount;
            $transaction['is_latest']  = 1;
            $transaction['trans_details']  = serialize(
                [
                    'trans_id'=>$request->trans_id,
                    'payer'=> 'card',
                    'payer_name'=>$request->payer_name,
                    'payee'=> $request->payer,
                    'merchant_id'=>$request->merchant_id,
                    'amount'=>$request->amount,
                    'currency'=> $request->currency,
                    'shipping_address'=>$request->shipping_address,
                ]);
            // update transaction
            $transaction = Transaction::create($transaction);

            // shopping cart history

            $historyData = array();
            foreach($cartContents as $cartItem) {

                $input['transaction_id']        = $transaction->id;
                $input['product_id']            = $cartItem->id;
                $input['quantity']              = $cartItem->quantity;
                $input['amount_per_item']       = $cartItem->price;
                $input['created_at']            = Carbon::now();
                $input['updated_at']            = Carbon::now();
                array_push($historyData, $input);
            };
            ShoppingCartHistory::insert($historyData);

//            update shopping cart and clear

                $this->sendPaymentSuccessEmail(Auth::user()->email,'user');
                $this->sendPaymentSuccessEmail('','admin');
            ShoppingCart::whereId($data->id)->update(array('payment_status' => 'complete'));
            (Auth::id())?Cart::session(Auth::id())->clear():Cart::clear();
            Session::flash('success', 'Your order has been placed successfully');
            return ['status' => true ,'message' => 'Your have made payment successfully.'];
        }else{
            return ['status' => false ,'message' => 'Something went wrong.'];
        }
    }

    private function updateCartInDB($cart){
        $result = ShoppingCart::updateOrCreate(['user_id' => Auth::id(), 'payment_status' => 'pending'],
            ['user_id' => Auth::id(), 'cart_details' => serialize($cart)]);
    }

    public function saveUserInfo(Request $request){
        $formData = array();
        parse_str($request->all()['formData'], $formData);

        $userUpdate = [
            'first_name' => $formData['first_name'],
            'last_name' => $formData['last_name'],
            'phone' => $formData['phone']
        ];
        $user = User::findOrFail(Auth::id());

        if (Auth::user()->type != 'dropshipper') {
            $user->update($userUpdate);
        }
        $userUpdate['type'] = $user->type;

        $userData = ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->update(['user_details' => serialize($formData)]);
        return ['status' => true ,'message' => 'User info saved successfully'];
    }

    /*my ordres view*/
    public function myOrders()
    {
        return view('cart.myOrders');
    }

    /*my orders details*/
    function getMyOrders(Request $request)
    {
        $myOrders = Transaction::with(['cart', 'purchasedItems.product.product_images'])->where('user_id', Auth::id())->get();
        return view('cart.getMyOrders', compact('myOrders'));
    }

    /*transaction details view*/
    public function transactionDetails()
    {
        return view('cart.transactionDetails');
    }

    /*get transaction details*/
    public function getTransactionDetails(Request $request)
    {
        $transaction = Transaction::where('paypal_id', $request->id)->first();
        $trans_details = unserialize($transaction->trans_details);

        return ['status' => true, 'data' => $trans_details];
    }

    public function trackOrder()
    {
        return view ('cart.trackOrder');
    }

    public function OrderStatus(Request $request)
    {
        $orderId    = $request->orderId;
        $email      = $request->email;
        $transaction = Transaction::with('cart')->where('paypal_id', $orderId)->first();
        if($transaction){
            if($transaction->cart->delivery_status == 'pending'){
                $html = '<p>Your order against your order id <b>'.$orderId.'</b> is pending and will be delivered soon.</p>';
            }elseif($transaction->cart->delivery_status == 'dispatched'){
                $html = '<p>Your order against your order id <b>'.$orderId.'</b> is on your way.</p>';
            }else{
                $html = '<p>Your order against your order id <b>'.$orderId.'</b> has been delivered.</p>';
            }
        }else{
            $html = 'No record found aganst order id <b>'.$orderId.'</b>';
        }
        return ['status' => true, 'html' => $html];
    }

    public function paymentFromWallet(Request $request)
    {


        $data = ShoppingCart::where(['user_id' => Auth::id(), 'payment_status' => 'pending'])->first();

        if($data){
            $cartContents   = (Auth::id())?Cart::session(Auth::id())->getContent():Cart::getContent();

            $tax        = 0;
            $cost       = 0;
            $discount   = 0;
            foreach($cartContents as $row){
                $product = Product::with('tax_rate')->findOrFail($row->id);

                $qty        = $row->quantity;
                $tax        = ($tax + (($product->tax_rate->rate / 100) * $product->price) * $qty);
                $cost       = ($cost + ($product->cost * $qty));
                $discount   = $discount + (($product->price - $product->discountedPrice) * $qty);

                $totalQty   = (Auth::id())?Cart::session(Auth::id())->getTotalQuantity():Cart::getTotalQuantity();
            }

//            make transaction entry

            $this->updateUserStatus();
            $walletId= WholesellerWallet::create([
                'debit' =>  $request->amount,
                'user_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $transaction['user_id']   = Auth::id();
            $transaction['cart_id']   = $data->id;
            $transaction['qty']       = $totalQty;
            $transaction['cost']      = $cost;
            $transaction['discount']  = $request->discount;
            $transaction['tax']       = $request->vat_amount;
            $transaction['paypal_id'] = $walletId->id;
            $transaction['amount']    = $request->amount;
            $transaction['is_latest']  = 1;
            $transaction['trans_details']  = serialize(
                [
                    'trans_id'=>$walletId->id,
                    'payer'=> 'wallet',
                    'payer_name'=> Auth::user()->email,
                    'payee'=> Auth::user()->email,
                    'merchant_id'=>$walletId->id,
                    'amount'=> $request->amount,
                    'currency'=>'£',
                    'shipping_address'=> '',
                ]);
            // update transaction
            $transaction = Transaction::create($transaction);

            // shopping cart history

            $historyData = array();
            foreach($cartContents as $cartItem) {

                $input['transaction_id']        = $transaction->id;
                $input['product_id']            = $cartItem->id;
                $input['quantity']              = $cartItem->quantity;
                $input['amount_per_item']       = $cartItem->price;
                $input['created_at']            = Carbon::now();
                $input['updated_at']            = Carbon::now();
                array_push($historyData, $input);
            };
            ShoppingCartHistory::insert($historyData);


    $this->sendPaymentSuccessEmail(Auth::user()->email,'user');
                $this->sendPaymentSuccessEmail('','admin');
//            update shopping cart and clear
            ShoppingCart::whereId($data->id)->update(array('payment_status' => 'complete'));
            (Auth::id())?Cart::session(Auth::id())->clear():Cart::clear();
            Session::flash('success', 'Your order has been placed successfully');
            return ['status' => true ,'message' => 'Your have made payment successfully.'];
        }else{
            return ['status' => false ,'message' => 'Something went wrong.'];
        }
    }

    public function updateUserStatus()
    {
        return User::where('id', Auth::user()->id)->update([
            'is_latest' => 1
        ]);
    }

    public function updateShipment()
    {


        $product = Product::with('shipping')->find(request()->product_id);
        $courier = Courier::whereId(\request()->shipment_id)->first();



        $shipping = new \Darryldecode\Cart\CartCondition(array(
            'name' => $courier->name ,
            'type' => 'shipping',
            'target' => 'total',
            'value' => $courier->charges,

             'attributes' =>  ['courier_id' => request()->shipment_id]
        ));

          Cart::session(Auth::id())->update(request()->product_id, array(
              'conditions' =>  $shipping
        ));
        $cart = Cart::session(Auth::id())->getContent()->values()->toArray();

        $this->updateCartInDB($cart);
        return ['status' => true, 'message' => 'Successfully updated'];
    }

    public function sendPaymentSuccessEmail($email='',$type){
         $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => $email,
            'email_subject' => 'New Order',
            'user_name'     => 'User',
            'final_content' => '<p><b>Dear Admin</b></p>
                                    <p>You order has been placed successfully</p>',
        ];
        if($type ==='admin'){
            $data['email_to'] ='khaleelrehman110@gmail.com';
            $data['email_subject'] = 'New Order';
            $data['user_name'] = 'User';
            $data['final_content'] = '<p><b>Dear Admin</b></p>
                                    <p>New Order is Placed</p>';
        }

        Email::sendEmail($data);
        return true;
    }

    public function getInvoiceDetail($id) {
           $id = decodeId($id);

         $couriers = Courier::pluck('name', 'id')->prepend('Select Courier', '');
        $order = Transaction::with(['cart', 'purchasedItems.product.product_images'])->find($id);

        return view('invoice', compact('order', 'couriers'));
    }

    public function returnOrder(Request $request)
    {
        if ($request->filled('order_id')) {
            $orderId = $request->order_id;
            $order = Transaction::find($orderId);
            if ($order) {
                $order->refund_request = 1;
                $order->save();
                
                Session::flash('success', 'Your order refund request has been sent to admin');
                return redirect('my-orders');
            }
            
        }
        
        Session::flash('error', 'Order not found!');
        return redirect('my-orders');
    }
}


