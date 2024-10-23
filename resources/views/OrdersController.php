<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\WholesellerWallet;
use App\Models\Transaction;
use App\Models\Courier;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Session, Hashids, DataTables;
use App\Models\Email;
use App\Models\TaxRate;
use App\Models\CouriersAssignmentDetail;
use Illuminate\Support\Carbon;
use App\Http\Controllers\CartController;

class OrdersController extends Controller
{

    public $resource = 'admin/orders';

    public function __construct()
    {
        $this->middleware('permission:view orders', ['only' => ['index']]);
        $this->middleware('permission:change order status', ['only' => ['changStatus']]);
        $this->middleware('permission:view order invoice', ['only' => ['show']]);
        $this->middleware('permission:change order invoice courier', ['only' => ['updateProductCourier']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
             $user_id ='';

            $id = $request->input('user_id');
            $orders = Transaction::with(['cart.user', 'purchasedItems.product.product_images'])->dateFilter();
            if ($id) {
                $user_id = decodeId($id);

                $orders =$orders->whereUserId($user_id)->orderBy('id', 'desc');
            } else {
                $orders =$orders->orderBy('id', 'desc');
            }

            return Datatables::of($orders->get())
                ->addColumn('orders_details', function ($order) {
                    return '<span class="details-control"></span>';
                })
                ->addColumn('orderId', function ($order) {
                    return "<u><a href = 'javascript:void(0)' onclick='load_model(" . '"' . $order->paypal_id . '"' . ")'>" . $order->paypal_id . "</a></u>";
                })
                ->addColumn('email', function ($email) {
                    $color = '';

                    if($email['is_latest']){
                        $color='red';
                    }else{
                        $color ='black';
                    }
                    $email = $email->cart->user->email??'';
                    return "<a style='color: $color'>$email</a>";
                })
                ->addColumn('amount', function ($order) {
                    $color ='';
                    if($order['is_latest'] and !$order['is_refunded']){
                        $color='red';
                    }elseif($order['is_refunded']){
                          $color='green';
                    }else{
                        $color ='black';
                    }
                    $amount = $order->amount > 0 ? $order->amount : 0;
                    return "<a style='color: $color'>".'£'.".$amount</a>";
                })
                ->addColumn('barcode_image', function ($order) {
                    $user =  User::where('id',$order->user_id)->first();

                    if($user and $order->label_image and $user->type !='wholesaler'){
                        return '<a href="'.url($order->label_image).'" download><img src="'.url($order->label_image).'" alt="'.$order->label_image.'" width="100" height="200"></a>';
                    }else{
                        return '';
                    }
                })
                ->addColumn('status', function ($order) {
                    
                    if ($order->is_refunded == 1) {
                        return '<a href="javascript:void(0)" class="badge bg-success" >Refunded</a>';
                    } else if ($order->refund_request == 1) {
                        return '<a href="javascript:void(0)" class="badge bg-danger" >Request for Refund</a>';
                    } else {
                    
                        $options = '<option value="">Select Status</option>';

                            
                         $cartid=$order->cart['id'];
                         $transaction_id = $order->id;
                        foreach (['pending','dispatched','delivered'] as $value){
                             $selected ='';
                             if($value ==='pending'){
                                  $selected = ($order->cart and $order->cart->delivery_status == "pending") ? "selected" : "";
                            
                             }
                             
                             if ($value ==='dispatched'){
                                 $selected = ($order->cart and $order->cart->delivery_status == "dispatched") ? "selected" : "";
                          
                             }
                             
                             if($value ==='delivered'){
                                   $selected = ($order->cart and $order->cart->delivery_status == "delivered") ? "selected" : "";
                             }
                          
                            $options.=' <option   '.$selected.' value='.$value.'>'.$value.'</option>';
                        }
                      
                      return '<select name="status" class="status_update" data-id='.$cartid.' data-transaction-id='.$transaction_id.'>
                               ' . $options . '
                            </select>';
                    }

                })
                ->addColumn('courier_service', function ($order) {
                    if ($order->refund_request == 1 || $order->is_refunded == 1) {
                        return '-';
                    } else {
                        $couriers = Courier::all();
                        $options = '<option value="">Select Courier</option>';

                       $user =  User::where('id',$order->user_id)->first();

                        foreach ($couriers as $value){
                            $selected='';
                            $disabled='';
                            if(!empty($value->type) and $value->type === $order->courier_type){
                                $selected="selected";
                            }
                            if(empty($value->type)){
                                $disabled='disabled';
                            }
                            $options.=' <option  '.$disabled.' '.$selected.' value='.$value->id.'>'.$value->name.'</option>';
                        }

                        if($user and $user->type !='wholesaler') {
                            $hashid = ($order->cart !=null)?$order->cart["hashid"]:"";
                            return '<select name="status" class="courier_send" data-id="' . $hashid . '" data-cart-id="' . Hashids::encode($order->id) . '">
                               ' . $options . '
                            </select>';
                        }else{
                            return '';
                        }
                    }

                })
                ->addColumn('action', function ($order) use ($id)   {
                    $label='';
                    if($order->label_image){
                        $label = url($order->label_image);
                    }
                    $action = '';

                    $action .='<a href="orders/' . Hashids::encode($order->id) . '" class="text-success btn-order" data-toggle="tooltip" title="View Invoice"><i class="fa fa-eye fa-lg fa-lg"></i></a>';
                    $action .='<a href="javascript:void(0)" class="text-info btn-order addViewOrderNote" data-id="'. $order->id .'" data-note="'. $order->note .'" data-toggle="tooltip" title="Add/View Note"><i class="fa fa-sticky-note-o fa-lg"></i></a>';
                    $action .='<a href="' .url('admin/update-status-order/'.Hashids::encode($order->id) ). '" class="text-success btn-order" data-toggle="tooltip" title="Update Status"><i class="fa fa-edit fa-lg"></i></a>';
                    $action .='<a href="' .url('admin/update-delivery-status/'.Hashids::encode($order->cart_id) ). '" class="text-success btn-order" data-toggle="tooltip" title="Update Delivery Status"><i class="fa fa-edit fa-lg"></i></a>';
                    
                    if(!$order->is_refunded && $order->refund_request) {
                        $queryString['order_id'] =  $order->id;
                        if ($id)
                           $queryString['user_id'] =  $id; 
                           
                        $action .='<a href="' .route('admin.refund.order', $queryString). '" class="text-danger btn-order" data-toggle="tooltip" title="Refund Order"><i class="fa fa-arrow-left"></i></a>';
                    }
                    
                    if(!empty($label)){
                        $action .='|<a href="javascript:void(0)" image-url="'.$label.'" class="text-success bt-download" data-toggle="tooltip" title="Download"><i class="fa fa-print fa-lg"></i></a>';
                    }
                    return $action;
                })
                ->rawColumns(['discounted_price','amount', 'orders_details', 'orderId', 'email', 'status', 'action','barcode_image','courier_service'])
                ->make(true);
        }

        return view($this->resource . '/index');
    }

    /**
     * change status
     *
     *
     */
    public function changStatus(Request $request)
    {
        $id = decodeId($request->id);

        ShoppingCart::where('id', $id)->update(['delivery_status' => $request->status]);
        return 'true';
    }
    
    public function saveOrderNote(Request $request)
    {
        $id = $request->id;
        $note = $request->note;
        $order = Transaction::find($id);
        if ($order) {
            $order->note = $note;
            $order->save();
        }
        return 'true';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->resource . '/create');
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $id = decodeId($id);

        $couriers = Courier::pluck('name', 'id')->prepend('Select Courier', '');
        $order = Transaction::with(['cart', 'purchasedItems.product.product_images'])->find($id);
        
        $courier_assign = courierDetailData($order->cart_id);
        $cartContents = unserialize($order->cart->cart_details);
        
        $totalShipmentCharges = 0;
        if(@$courier_assign->status == 2 ) {
            $cartContents = $this->attach_shipment_charges($cartContents, $order->cart_id);
            $totalShipmentCharges = $cartContents['shipment_charges'];
            unset($cartContents['shipment_charges']);
        }
        
        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;
        
//        if ($order->paypal_id == 173) {
//            dump($cartContents[0]['conditions']->getName());
//            dd($order->toArray());
//        }

        return view($this->resource . '/invoice', compact('order', 'couriers','vatCharges', 'totalShipmentCharges', 'cartContents'));
    }
    
    public function getOrderDetails($id)
    {
        $order = Transaction::with(['cart', 'purchasedItems.product.product_images'])->find($id);
        
        $cartContents = unserialize($order->cart->cart_details);

        $products = [];
        foreach ($cartContents as $cartData) {
            $product['product_name'] = $cartData['name'];
            $product['product_quantity'] = $cartData['quantity'];
            $product['courier_name'] = @$cartData['conditions']->getName();
            $product['courier_charges'] = $cartData['conditions']->getValue()??0;

            $products[] = $product;
        }

        return response()->json([
            'status' => true,
            'products'  => $products
        ]);
    }
    
     public function attach_shipment_charges($cartContents, $cartId)
    {
        $courier_id=0;
        $temp=0;
        $shipment_charges=0;
        $pre_key=0;
        
        foreach($cartContents as $key=> $item)
        {
            $courierAssignmentDetail = CouriersAssignmentDetail::where('product_id', $item['id'])->where('cart_id', $cartId)->first();
            
            if($courier_id == 0)
            {
                $courier_id = @$courierAssignmentDetail->group_no;
                if (@$courierAssignmentDetail->couriers) {
                    $shipment_charges = $shipment_charges + $courierAssignmentDetail->couriers->charges;
                    $cartContents[$key]['charges_check'] = 2;
                }
            }

            if($courier_id != @$courierAssignmentDetail->group_no)
            {
                $cartContents[$pre_key]['charges_check'] = 1;
                $courier_id = $courierAssignmentDetail->group_no;
                $shipment_charges = $shipment_charges + $courierAssignmentDetail->couriers->charges;
            }
            
            $pre_key=$key;
        }

        $cartContents[$pre_key]['charges_check']=1;
        $cartContents['shipment_charges']=$shipment_charges;
        
        return $cartContents;
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
    }

    /**
     * change status
     *
     *
     */
    public function updateProductCourier(Request $request)
    {
        $cart = ShoppingCart::where('id', $request->cart_id)->first();
        if ($cart) {
            $cart_details = [];
            $products = unserialize($cart->cart_details);
            foreach ($products as $product) {
                if ($product['id'] == $request->product_id) {
                    unset($product['courier']);
                    if ($request->courier_id > 0)
                        $product['courier'] = Courier::find($request->courier_id)->toArray();

                    $cart_details[] = $product;
                } else {
                    $cart_details[] = $product;
                }
            }
            $cart->cart_details = serialize($cart_details);
            $cart->save();
        }

        return 'true';
    }

    public function changeCourier()
    {
        $courier = Courier::where('id',request()->status)->first();

        $cartid = decodeId(\request()->cart_id);
        switch ($courier->type){
            case 'hermes';
                return $this->hermesCourierSystem($cartid,$courier->type);
                break;
            default:
                return 'false';
        }
    }

    public function hermesCourierSystem($cartid,$type)
    {
       
        $order = Transaction::with(['cart', 'purchasedItems.product.product_images'])->find($cartid);
        $cart = @$order->cart;
        $user = unserialize(@$cart->user_details);

        $dataRecive = (new \App\Http\Controllers\HomeController())->createParcel($user);
        
        Transaction::where('id', $cartid)->update([
            'barcode' => $dataRecive['barcode'],
            'label_image' => $dataRecive['image'],
            'courier_type' => $type
        ]);
        return 'true';
    }
    
    public function orderPrint($id)
    {
        $id = decodeId($id);
    

        $couriers = Courier::pluck('name', 'id')->prepend('Select Courier', '');
        $order = Transaction::with(['cart', 'purchasedItems.product.product_images'])->find($id);
        
        $courier_assign = courierDetailData($order->cart_id);
        $cartContents = unserialize($order->cart->cart_details);
        
        $totalShipmentCharges = 0;
        if(@$courier_assign->status == 2 ) {
            $cartContents = $this->attach_shipment_charges($cartContents, $order->cart_id);
            $totalShipmentCharges = $cartContents['shipment_charges'];
            unset($cartContents['shipment_charges']);
        }
        
        $vatCharges=TaxRate::select('rate')->where('id',1)->first();
        $vatCharges=(int)$vatCharges->rate;
        return view($this->resource . '/invoice-print', compact('order', 'couriers','vatCharges', 'totalShipmentCharges'));
    }
    
    public function updateOrderStatus($id) {
        
         $cart = ShoppingCart::where('id', $id)->first();
         $cart->delivery_status = request()->status;
         $cart->save();
         $status = request()->status;
         $userData = User::whereId($cart->user_id)->first();
         $url =   url('/get-invoice-detail',Hashids::encode(request()->transaction_id));
         $user = 'khaleelrehman110@gmail.com';
         $data = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => $userData->email,
            'email_subject' => 'Order Delivery',
            'user_name'     => 'User',
            'final_content' => "<p><b>Dear User</b></p>
                                    <p>Your Order has been $status</p><br><a href='$url'>Click Here For Invoice</>",
        ];
        
         $data1 = [
            'email_from'    => 'baadrayltd@gmail.com',
            'email_to'      => 'aqsintetrnationalstore@gmail.com',
            'email_subject' => 'Order Delivery',
            'user_name'     => 'User',
            'final_content' => "<p><b>Dear Admin</b></p>
                                    <p>An Order is been $status</p>",
        ];
      Email::sendEmail($data);
        Email::sendEmail($data1);
        return 'true';
    }
    
    public function refundOrder(Request $request)
    {
       $orders = Transaction::where('id',request()->order_id)->first();
 
        WholesellerWallet::create([
            'credit' => $orders->amount,
            'user_id' =>$orders->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $orders->is_refunded= true;
        $orders->save();
        
        $queryString = '';
        if ($request->filled('user_id')) {
            $queryString = '?user_id='.$request->user_id;
        }
        
        Session::flash('success', 'Order Refunded successfully!');
        return redirect($this->resource . $queryString);
    }
   
    
}
