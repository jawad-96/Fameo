<div class="row">
    <div class="col-lg-8">
        <div class="flat-row-title styl e1">
            <h3>Shopping Cart</h3>
        </div>
        <div class="table-cart">

            <table>
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Group</th>
                    <!-- <th>Shipping Charges</th> -->
                    <th>Total</th>

                </tr>
                </thead>
                <tbody>
                @forelse($courierAssignment->detail as $product)
                    <tr>
                        <td>
                            <div class="img-product">
                                <img src="{{ getProductDefaultImage($product->product_id) }}" alt="">
                            </div>
                            <div class="name-product">
                                {{$product->product->name}} <br />
                                {{--                                {{$product->product->code}}--}}
                            </div>
                            <div class="price">
                                {{$product->quantity . ' x £' . number_format($product->price,2)}}
                            </div>




                            <div class="col-md-12">
                                @if($product->group_no !=0)
                                    <input type="text" style="margin-top: 5%; font-weight: bolder " disabled="true" value="{{@$product->couriers->name}}">
                                @endif

                            </div>

                            {{--							<div class="clearfix"></div>--}}
                        </td>
                        <td>


                            <div class="col-md-12">
                                <div class="form-group" >
                                    @if($product->group_no == 0)
                                        <input type="checkbox" class="group_checkbox"  style="opacity: 100%;"  name="group_check[]" >
                                    @else
                                        {{--                                    <input type="checkbox"  style="opacity: 100%;" checked disabled >--}}
                                        <input type="hidden"  style="opacity: 100%;" value="1"  name="group_check[]" >
                                    @endif
                                    <input type="hidden" style="opacity: 100%;" value="{{@$product->group_no}}"  name="group_no[]" >


                                </div>


                            </div>


                        </td>

                        <td>
                            <div class="total">
                                £{{number_format($product->price * $product->quantity,2)}}
                                <input type="hidden"  name="product_id[]" class="product-id " value="{{$product->product_id}}"  >
                            </div>
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

        <div class="col-md-12">
            <div class="form-group" >
                <label class="font-weight-bold">Couriers <span class="stock_alert"></span></label>
                <select @if(@$courierAssignment->view) disabled="true" @endif  required class="form-control courier  "   name="couriers_id">
                    <option value="">Select one</option>
                    @foreach($couriers as $courier)
                        <option @if($courier->id == $product->couriers_id) selected @endif  value="{{$courier->id}}">{{$courier->name." | ".$courier->charges }}   </option>
                    @endforeach
                </select>
            </div>


        </div>

        <a href="{{url('admin/courier/reset/'.$courierAssignment->id.'/admin')}}" class="btn btn-info" title="">Reset</a>

    </div>
</div><!-- /.row -->

<script type="text/javascript">







    $(document).ready(function() {
        //update cart
        $(".update-cart").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var qty = $('.product'+id).val();
            show_loader();
            $.ajax({
                url: '{{ url('cart-update') }}',
                method: "patch",
                data: {id: id, quantity: qty},
                success: function (response) {
                    if(response.status){
                        $('#cartTotal').text(response.cartTotal);
                        $('#cartPrice').text("£"+response.cartPrice);
                        success_message("Cart updated successfully");
                        hide_loader();
                        getCartDetails();
                    }
                }
            });
        });
        // remove cart item
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            if(confirm("Are you sure")) {
                var id = $(this).attr('data-id');
                show_loader();
                $.ajax({
                    url: '{{ url('cart-remove') }}',
                    method: "DELETE",
                    data: {id: id},
                    success: function (response) {
                        $('#cartTotal').text(response.cartTotal);
                        $('#cartPrice').text("£"+response.cartPrice);
                        success_message("Item removed from cart successfully");
                        hide_loader();
                        getCartDetails();
                    }
                });
            }
        });
    });

</script>
