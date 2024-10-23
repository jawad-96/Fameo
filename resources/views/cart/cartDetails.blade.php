
<div class="row">
	<div class="col-lg-8">
		<div class="flat-row-title styl e1">
			<h3>Shopping Cart</h3>
            @if(Auth::user()->type == 'wholesaler')
            <div class="Row">
                @if(Auth::user()->percentage_1 > 0 && Auth::user()->quantity_1 >0  )
                <p class="bold">{{Auth::user()->percentage_1.'% off if Quantity of Each Item equal or gretaer than '.Auth::user()->quantity_1}}</p>
                @endif
                @if(Auth::user()->percentage_2 > 0 && Auth::user()->quantity_2 >0 )
                <p class="bold">{{Auth::user()->percentage_2.'% off if Quantity of Each Item equal or gretaer than '.Auth::user()->quantity_2}}</p>
                    @endif
                @if(Auth::user()->percentage_3 > 0 && Auth::user()->quantity_3 >0 )
                <p class="bold">{{Auth::user()->percentage_3.'% off if Quantity of Each Item equal or gretaer than '.Auth::user()->quantity_3}}</p>
                    @endif
            </div>
                @endif
		</div>
		<div class="table-cart">
			<table>
				<thead>
					<tr>
						<th>Product</th>
						<th>Quantity</th>
						<!-- <th>Shipping Charges</th> -->
						<th>Total</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				@forelse($cartContents as $product)
					<tr>
						<td>
							<div class="img-product">
								<img src="{{ getProductDefaultImage($product->id) }}" alt="">
							</div>
							<div class="name-product">
								{{$product->name}} <br />{{$product->code}}
							</div>
							<div class="price">
								{{$product->quantity . ' x £' . $product->price." "}}@if(@$product->cprice > $product->price)<s>{{"(".$product->cprice.")"}}</s>@endif
							</div>

							<div class="clearfix"></div>
                            @if((@$cart->courierAssignment && @$cart->courierAssignment->status == 2)   )

{{--                                {{dd($cart->courierAssignment,$product->courier_detail->couriers_id)}}--}}
                                <div class="col-md-12">
                                    <div class="form-group" >
                                        <label class="font-weight-bold"  >Couriers <span class=""></span></label>
                                        <select style="background-color: {{$product->color}} " disabled="true" class="form-control   "  required name="couriers_id[]">
                                            <option value="">Select one</option>
                                            @foreach($couriers as $courier)
                                                <option @if($courier->id == $product->courier_detail->couriers_id) selected @endif   value="{{$courier->id}}">{{$courier->name." | ".$courier->charges }}   </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif(Auth::user()->type == 'dropshipper' && ($count ==1 && $product->quantity == 1) )

{{--                                {{dd($cart->courierAssignment,$product->courier_detail->couriers_id)}}--}}
                                <div class="col-md-12">
                                    <div class="form-group" >
                                        <label class="font-weight-bold"  >Couriers <span class=""></span></label>
                                        <select style="background-color: {{$product->color}} " disabled="true" class="form-control   "  required name="couriers_id[]">
                                            <option value="">Select one</option>
                                            @foreach($couriers as $courier)
                                                <option @if($courier->id == $product->courier_id) selected @endif   value="{{$courier->id}}">{{$courier->name." | ".$courier->charges }}   </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


						</td>
						<td>
							<div class="quanlity">
								<span class="btn-down" data-id="product{{$product->id}}" data-product="{{$product->id}}"></span>
								<input type="text" name="number" class="qty product{{$product->id}}"  data-product="{{$product->id}}" data-quantity="{{getProductQuantity($product->id)}}" value="{{$product->quantity}}" min="1" max="{{getProductQuantity($product->id)}}" placeholder="Quantity" onkeyup="checkToUpdateCart(this)">
								<span class="btn-up"  data-id="product{{$product->id}}" data-product="{{$product->id}}"></span>
							</div>
						</td>
						<!-- <td>
							<div class="price">
{{--								£{{$product->getPriceSumWithConditions() - $product->getPriceSum()}}--}}
							</div>
						</td> -->
						<td>
							<div class="total">
								£{{$product->price * $product->quantity}}
                                {{--£{{(Auth::id())?number_format(Cart::session(Auth::id())->getSubTotal(), 2):number_format(Cart::getSubTotal(), 2)}}--}}
							</div>
						</td>
						<td>
							<!-- <a href="javascript:void(0)" class="remove-from-cart" title="" onclick="removeCartItem({{$product->id}})">
								<img src="{{asset('front_assets/images/icons/delete.png')}}" alt="">
							</a> -->
							<button class="btn btn-info btn-sm update-cart" data-id="{{$product->id}}"><i class="fa fa-refresh"></i></button>
							<button class="btn btn-danger btn-sm remove-from-cart" data-id="{{$product->id}}"><i class="fa fa-trash-o"></i></button>
						</td>
					</tr>
				@empty
					<tr><td colspan="4">No Record Found</td></tr>
				@endforelse
				</tbody>
			</table>
		</div><!-- /.table-cart -->
	</div><!-- /.col-lg-8 -->
    <div class="col-lg-4">
        <div class="cart-totals">
            <h3>Cart Totals</h3>
            <form action="#" method="get" accept-charset="utf-8">
                <table>
                    <tbody>
                    <tr>
                        <td>Product Total</td>

                        <td class="subtotal">£{{number_format($originalPrice,2)}}</td>
                    </tr>
                    @if(Auth::user()->type == 'dropshipper')
                    <tr>
                        <td>Product Vat</td>
                        <td class="subtotal">£{{number_format(($originalPrice*$vatCharges)/100,2)}}</td>
                        @php 
                        $subTotal=number_format($subTotal+(($subTotal)*$vatCharges)/100,2) 
                        @endphp
                    </tr>
                    @endif

                    @if(Auth::user()->type == 'retailer')
                    <tr style="display:none">
                        <td>Product Vat</td>
                        <td class="subtotal">£{{number_format(($subTotal*$vatCharges)/100,2)}}</td>
                    </tr>
                        @php 
                            $pVat = (($subTotal)*$vatCharges)/100;
                            $subTotal=number_format($subTotal,2);
                        @endphp
                    @endif

                    <tr>
                        @if(Auth::user()->type == 'dropshipper')
                            <td>Shipping Charges</td>
                            <td >£{{number_format(@$total_shipment_charges,2) }}</td>
                        @elseif(Auth::user()->type == 'wholesaler')
                            <td>Shipping Charges</td>
                            <td class="subtotal">£0</td>
                        @else
                            <td>Shipping Charges</td>
                            <td >Free</td>
                            {{-- <td class="subtotal">£{{number_format(($subTotal - $cartSum) ,2)}}</td> --}}
                        @endif
                    </tr>

                    @if(Auth::user()->type == 'dropshipper')
                        <tr>
                            <td>Shipping Tax</td>
                            <td class="subtotal">£{{number_format(($total_shipment_charges*$vatCharges)/100,2)}}</td>
                            @php 
                                $total_shipment_charges=number_format($total_shipment_charges+(($total_shipment_charges)*$vatCharges)/100,2);
                            @endphp
                        </tr>
                    @endif

                    @if(Auth::user()->type == 'retailer')
                    <?php
                        $disc = $originalPrice - $subTotal;
                        if ($disc > 0) {
                    ?>
                    <tr>
                        <td>Discount</td>
                        <td class="subtotal">£{{number_format($disc,2)}}</td>
                    </tr>
                    <?php } ?>
                    @endif

                    @if(Auth::user()->type == 'wholesaler')
                    <tr>
                        <td>Discount</td>
                        <td class="subtotal">£{{number_format($originalPrice - $subTotal,2)}}</td>
                    </tr>
                    <tr>
                        <td>Vat</td>
                        <td>£{{number_format(($subTotal*$vatCharges)/100,2)}}</td>
                        @php $subTotal=number_format($subTotal+($subTotal*$vatCharges)/100,2) @endphp
                    </tr>
                    @endif
                    <tr>
                        <td>Total</td>
                        <td class="price-total">£{{$subTotal}}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="btn-cart-totals">

                    <?php 
                        $type = Auth::user()->type;    
                    ?>

                    @if($count > 0)
                        @if($type == 'dropshipper' && is_null($cart->courierAssignment) && $count == 1 && $product->quantity == 1)
                            <a href="{{url('products')}}" class="update" title="">Continue Shopping</a>
                            <a href="{{url('make-payment')}}" class="checkout mb-4 ptc1" title="">Proceed to Checkout</a>
                            <a href="{{url('proceed-admin-request')}}" class="checkout" title="">Request for Courier Change</a>
                        @elseif($type == 'dropshipper' && ($cart->courierAssignment == 'null' || @$cart->courierAssignment->status == 0))
                            <a href="{{url('products')}}" class="update" title="">Continue Shopping</a>
                            <a href="{{url('proceed-admin-request')}}" class="checkout rta1" title="">Request to Admin</a>
                        @elseif(@$cart->courierAssignment->status == 1)
                            <a  class="proceed-admin disabled" title="">Waiting For Approval</a>
                        @elseif( @$cart->courierAssignment->status == 2   )
                            <a href="{{url('cancel-request/'.$cart->courierAssignment->id.'/customer')}}" class="update" title="">Cancel Order</a>
                            <a href="{{url('make-payment')}}" class="checkout ptc2" title="">Proceed to Checkout</a>
                        @elseif( @$cart->courierAssignment->status == 3)
                            <a href="{{url('products')}}" class="update" title="">Continue Shopping</a>
                            <a href="{{url('proceed-admin-request')}}" class="checkout  rta2" title="">Request to Admin</a>
                            <a  class="proceed-admin" title="">Order Canceled</a>
                        @elseif( @$cart->courierAssignment->status == 4)
                            <a href="{{url('products')}}" class="update" title="">Continue Shopping</a>
                            <a href="{{url('proceed-admin-request')}}" class="checkout  rta3" title="">Request to Admin</a>
                        @elseif($type != 'dropshipper' || ($count == 1 && $product->quantity == 1) )
                            <a href="{{url('products')}}" class="update" title="">Continue Shopping</a>
                            <a href="{{url('make-payment')}}" class="checkout mb-4 ptc3" title="">Proceed to Checkout</a>
                        @endif
                    @endif
                </div><!-- /.btn-cart-totals -->
            </form><!-- /form -->
        </div><!-- /.cart-totals -->
    </div><!-- /.col-lg-4 -->
</div><!-- /.row -->

<script type="text/javascript">
    $(document).ready(function() {

        $(".quanlity span.btn-down").click(function(){
            var productId = $(this).attr('data-id');
            var input = $(".quanlity input."+productId);
            var id = $(this).attr('data-product');
            input.val(parseInt(input.val())-1);
            if(parseInt(input.val())<parseInt(input.attr("max"))){
                updateCartItems(id,input.val());
            }else{
                error_message("You have added maximum "+input.attr("max")+" quantity");
            }


        });
        $(".quanlity span.btn-up").click(function(){
            var productId = $(this).attr('data-id');
            var input = $(".quanlity input."+productId);
            var id = $(this).attr('data-product');
            if(parseInt(input.val())<parseInt(input.attr("max"))){
                input.val(parseInt(input.val())+1);
                updateCartItems(id, input.val());
            }else{
                error_message("You have added maximum "+input.attr("max")+" quantity");
            }
        });
        $(".update-cart").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var qty = $('.product'+id).val();
            var totalAvailable = $('.product'+id).attr('data-quantity');
            if(qty>totalAvailable){
                toastr.error('Quantity is greater than available stock');
            }else{
                updateCartItems(id,qty);
            }


        });
        // remove cart item
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            if(confirm("Are you sure you want to delete this item ?")) {
                var id = $(this).attr('data-id');
                show_loader();
                $.ajax({
                    url: '{{ url('cart-remove') }}',
                    method: "DELETE",
                    data: {id: id},
                    success: function (response) {
                        // if(response.status) {
                        //     $('#cartTotal').text(response.cartTotal);
                        //     $('#cartPrice').text("£" + response.cartPrice);
                        //     success_message("Item removed from cart successfully");
                        //     hide_loader();
                        //     getCartDetails();
                        // }
                        // else
                        // {
                        //     success_message("Item removed from cart successfully");
                        // }


                        if(response.status) {
                            $('#cartTotal').text(response.cartTotal);
                            $('#cartPrice').text("£" + response.cartPrice);
                            success_message("Item removed from cart successfully");
                            hide_loader();
                            getCartDetails();
                        } else {
                            $('#cartTotal').text(response.cartTotal);
                            $('#cartPrice').text("£" + response.cartPrice);
                            getCartDetails();
                            hide_loader();
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });

    });

    function checkToUpdateCart(event){
        var currentValue = $(event).val();
        var totalAvailableProducts = $(event).attr('data-quantity');
        var id = $(event).attr('data-product');
        if(currentValue>parseInt(totalAvailableProducts)){
            var va = 0;
            $(event).val(va);
            toastr.error('Quantity is greater than available stock');

        }else{
            updateCartItems(id,currentValue);
        }

    }

    function updateCartItems(id,qty) {
        show_loader();
        $.ajax({
            url: '{{ url('cart-update') }}',
            method: "patch",
            data: {id: id, quantity: qty},
            success: function (response) {
                if(response.status) {
                    $('#cartTotal').text(response.cartTotal);
                    $('#cartPrice').text("£"+response.cartPrice);
                    success_message("Cart updated successfully");
                    hide_loader();
                    getCartDetails();
                } else {
                    hide_loader();
                    toastr.error("Sorry You Previous Request Already In Process");
                }
            }
        });
    }


</script>
