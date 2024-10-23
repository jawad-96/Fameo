<?php
    $vatCharges = getVatCharges();
?>


<div class="wishlist-content">
    <table class="table-wishlist">
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Price</th>
                <th>Quantity</th>
                <th>Delivery Status</th>
                <th>Transaction Details</th>
            </tr>
        </thead>
        <tbody>
        @forelse($myOrders as $myOrder)
           <tble>
               <tr>
                   <td> <span style="font-weight: bold;">Order Id : {{$myOrder->paypal_id}}</span></td>
               </tr>
           </tble>
            <?php
                $cart = @$myOrder->cart;
                $user = unserialize(@$cart->user_details);

                $cart_details = unserialize(@$cart->cart_details);
                $courierAssignment = courierDetailData($cart->id); 
                $subtotal = 0;
                $courier=0;
                $courierAmout =0;
            ?>
            @forelse($myOrder->purchasedItems as $order_item)
                    @if(!$order_item->product)
                        @php continue;@endphp

                        @endif
                        
                @php
                    
                    $single_item = collect($cart_details)->where('id', $order_item->product->id)->first();
                    $productVat = getOrderProductTaxAmount($myOrder->purchasedItems, $single_item['id']);
                    // dd($single_item);
                    
                    $courier = 0;
                    if (@$single_item['conditions']) {
                        $courier = $single_item['conditions']->getValue()??0;
                    }
                    

                    if(@$courierAssignment->status == 2){
                        $courierDetailDataCharges =courierDetailDataCharges($single_item['id'],$cart->id);
                        if (@$courierDetailDataCharges->couriers) {
                            $courier = @$courierDetailDataCharges->couriers->charges;
                        }
                    }
                    
                    $courierAmout = ($courier + $courierAmout );
                    
                    $unit_price = $single_item['price'];
                    $courierVat = 0;
                    if ($courier > 0 && $vatCharges > 0) {
                    $courierVat = $courier * ($vatCharges/100);
                    }
                    
                    
                   $item_sub_total = $unit_price * $single_item['quantity'];
                   $subtotal = ($subtotal + $item_sub_total);
                   $item_discount = (@$single_item['item_discount'])?$single_item['item_discount']:0;
                   $item_sub_total = $item_sub_total - $item_discount;   
                   $item_sub_total = $item_sub_total + $productVat + $courier +  $courierVat;
               @endphp
            <tr>
                <td>
                    <div class="delete">
                        {{$order_item->product->sku??''}}
                    </div>
                    <div class="product">
                        <a href="{{ url('products/'.Hashids::encode($order_item->product->id)) }}" class="image">
                            <img src="{{ $order_item->product->default_image_url }}" alt="">
                        </a>
                        <div class="name">
                            {{$order_item->product->name}} <br />{{$order_item->product->code}}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="price">
                        <span class="sale">£{{ number_format($item_sub_total,2) }}</span>
                    </div>
                </td>
                <td>
                    <div class="status-product">
                        {{$order_item->quantity}}
                    </div>
                </td>
                <td>
                    <div class="status-product">
                        @if($myOrder->is_refunded == 1)
                            <b style="color:#5cb85c;">Refunded</b>
                        @elseif($myOrder->refund_request == 1)
                            <b  style="color:#d9534f;">Request sent for refund</b>
                        @else
                            {{$myOrder->cart->delivery_status}}
                        @endif
                    </div>
                </td>
                <td style="min-width: 170px;">
                    <!-- <div class="status-product">
                           <a class="transaction-details" href="{{url('transaction-details').'/'.$myOrder->paypal_id}}">Details</a>
                    </div> -->
                
                    <div class="status-product row">
                        {{-- $myOrder->cart->delivery_status =='pending' &&  --}}
                        @if($myOrder->refund_request == 0)
                           <a class="transaction-details btn btn-primary btn-sm" href="{{route('return.order',['order_id' => $myOrder->id,'cart_id' => $myOrder->cart->id])}}" >Refund Order</a> |
                        @endif
                           <a class="transaction-details btn btn-primary btn-sm" href="javascript:void(0)" onclick="transactionDetails('{{$myOrder->paypal_id}}')">Details</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3">No Records Found</td></tr>
            @endforelse
        @empty
            <tr><td colspan="3">No Records Found</td></tr>
        @endforelse

        </tbody>
    </table><!-- /.table-wishlist -->
</div><!-- /.wishlist-content -->

