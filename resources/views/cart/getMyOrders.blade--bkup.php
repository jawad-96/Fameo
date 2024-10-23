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
            @forelse($myOrder->purchasedItems as $order_item)
                    @if(!$order_item->product)
                        @php continue;@endphp

                        @endif
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
                        <span class="sale">£{{$myOrder->amount }}</span>
                    </div>
                </td>
                <td>
                    <div class="status-product">
                        {{$order_item->quantity}}
                    </div>
                </td>
                <td>
                    <div class="status-product">
                           {{$myOrder->cart->delivery_status}}
                    </div>
                </td>
                <td>
                    <!-- <div class="status-product">
                           <a class="transaction-details" href="{{url('transaction-details').'/'.$myOrder->paypal_id}}">Details</a>
                    </div> -->
                    <div class="status-product">
                           <a class="transaction-details" href="javascript:void(0)" onclick="transactionDetails('{{$myOrder->paypal_id}}')">Details</a>
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

