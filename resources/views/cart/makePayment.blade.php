@extends('layouts.app')
@section('content')
    <section class="flat-checkout">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="box-checkout">
                        <form class="checkout" id="myForm">
                            <div class="billing-fields">
                                <div class="fields-title">
                                    <h3>Billing details</h3>
                                    <span></span>
                                    <div class="clearfix"></div>
                                    @php $vatAmount =0;@endphp
                                </div><!-- /.fields-title -->
                                <div class="fields-content">
                                    <div class="field-row">
                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="first_name">First Name *</label>
                                                <input type="text" id="first-name" name="first_name" class="form-control"
                                                    placeholder="First Name"
                                                    value="{{ Auth::user()->type != 'dropshipper' ? Auth::user()->first_name : '' }}"
                                                    required>
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="last_name">Last Name *</label>
                                                <input type="text" id="last-name" name="last_name" class="form-control"
                                                    placeholder="Last Name"
                                                    value="{{ Auth::user()->type != 'dropshipper' ? Auth::user()->last_name : '' }}"
                                                    required>
                                            </p>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="field-row form-group">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" id="company-name" name="company_name" class="form-control"
                                            value="{{ $userData['company_name'] ?? '' }}"
                                            {{ Auth::user()->type == 'wholesaler' ? 'required' : '' }}>
                                    </div>
                                    <div class="field-row">
                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="email_address">Email Address *</label>
                                                <input type="email" id="email-address" name="email_address"
                                                    class="form-control" value="{{ Auth::user()->email }}" readonly>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="phone">Phone *</label>
                                                <input type="text" id="phone" name="phone" class="form-control"
                                                    value="{{ Auth::user()->phone }}"required>
                                            </p>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="field-row form-group">
                                        <label for="address">Address *</label>
                                        <input type="text" id="address" name="address" placeholder="Street address"
                                            class="form-control" value="{{ $userData['address'] ?? '' }}" required>
                                        <input type="text" id="address-2" name="address_2"
                                            placeholder="Apartment, suite, unit etc. (optional)" class="form-control">
                                    </div>
                                    <div class="field-row form-group">
                                        <label for="town_city">Town / City *</label>
                                        <input type="text" id="town-city" name="town_city" class="form-control"
                                            value="{{ $userData['town_city'] ?? '' }}" required>
                                    </div>
                                    <div class="field-row form-group">
                                        <p class="field-one-half">
                                            <label for="state_country">State / County *</label>
                                            <input type="text" id="state-country" name="state_country"
                                                class="form-control" value="{{ $userData['state_country'] ?? '' }}"
                                                required>
                                        </p>
                                        <p class="field-one-half form-group">
                                            <label for="post_code">Postcode / ZIP *</label>
                                            <input type="text" id="post-code" name="post_code" class="form-control"
                                                value="{{ $userData['post_code'] ?? '' }}" required>
                                        </p>
                                        <div class="field-row">
                                            <label>Country *</label>
                                            <select name="country" class="" required>

                                                <option value="United Kingdom">United Kingdom</option>

                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-box">
                                        <button type="submit" class="save-to-proceed">Click To Proceed</button>
                                    </div>
                                    <!-- <div class="checkbox">
                     <input type="checkbox" id="create-account" name="create-account" checked>
                     <label for="create-account">Create an account?</label>
                    </div> -->
                                </div><!-- /.fields-content -->
                            </div><!-- /.billing-fields -->
                        </form><!-- /.checkout -->
                    </div><!-- /.box-checkout -->
                </div><!-- /.col-md-7 -->
                <div class="col-md-5">
                    <div class="cart-totals style2">
                        <h3>Your Order</h3>
                        <table class="product">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cartContents as $key =>$product)
                                    <tr>
                                        <td style="background-color: {{ @$product->color }}">{{ @$product->name }}</td>
                                        {{--									<td>£{{$product->price.' * '.$product->quantity.' = '. $product->price * $product->quantity}}</td> --}}

                                        @if (Auth::user()->type == 'wholesaler')
                                            <td>£{{ $product->price . ' * ' . $product->quantity . ' = ' . $product->price * $product->quantity }}
                                            </td>
                                        @else
                                            <td>£{{ number_format(getProductDetails($product->id)->price, 2) . ' * ' . $product->quantity . ' = ' . number_format(getProductDetails($product->id)->price * $product->quantity, 2) }}
                                            </td>
                                        @endif


                                    </tr>
                                    @if (@$product->charges_check == 1)
                                        <tr>
                                            <td style="background-color: {{ @$product->color }}">
                                                <b>{{ ('Courier :' . $product->courier_detail ? $product->courier_detail->couriers->name : '' . ' | £' . $product->courier_detail) ? $product->courier_detail->couriers->charges : 0 }}</b>
                                            </td>

                                            <td></td>

                                        </tr>
                                    @endif
                                @empty
                                    <td colspan="2">Cart is empty</td>
                                @endforelse
                            </tbody>
                        </table><!-- /.product -->
                        <table>
                            <tbody>

                                @if (Auth::user()->type == 'retailer')
                                    <tr style="display:none">
                                        <td>Product Vat</td>
                                        <td class="subtotal">£{{ number_format(($subTotal * $vatCharges) / 100, 2) }}</td>
                                    </tr>

                                    @php
                                        $pVat = ($subTotal * $vatCharges) / 100;
                                        $subTotal = number_format($subTotal, 2);
                                    @endphp

                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="subtotal">£{{ number_format($originalPrice, 2) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="subtotal">£{{ number_format($originalPrice, 2) }}</td>
                                    </tr>
                                @endif

                                @if (Auth::user()->type == 'dropshipper')
                                    <tr>
                                        <td>Subtotal Vat</td>
                                        @php $vatAmount = number_format(($originalPrice*$vatCharges)/100,2);@endphp
                                        <td class="subtotal">£{{ number_format(($originalPrice * $vatCharges) / 100, 2) }}
                                        </td>
                                        @php $subTotal=number_format($subTotal+(($subTotal)*$vatCharges)/100,2) @endphp
                                    </tr>
                                @endif

                                <tr>
                                    @if (Auth::user()->type == 'dropshipper')
                                        <td>Shipping</td>
                                        <td>£{{ number_format(@$total_shipment_charges, 2) }}</td>
                                    @elseif(Auth::user()->type == 'retailer')
                                        <td>Shipping</td>
                                        <td>Free</td>
                                    @else
                                        <td>Shipping </td>
                                        <td class="subtotal">£{{ number_format($subTotal - $cartSum, 2) }}</td>
                                    @endif
                                </tr>

                                @if (Auth::user()->type == 'dropshipper')
                                    <tr>
                                        <td>Shipping Vat</td>
                                        <td class="subtotal">
                                            £{{ number_format(($total_shipment_charges * $vatCharges) / 100, 2) }}</td>
                                        @php $total_shipment_charges=number_format($total_shipment_charges+(($total_shipment_charges)*$vatCharges)/100,2) @endphp
                                    </tr>
                                @endif

                                @if (Auth::user()->type == 'retailer')
                                    <?php
									$disc = $originalPrice - $subTotal;
									if ($disc > 0) {
								?>
                                    <tr>
                                        <td>Discount</td>
                                        @php $discountAmount = number_format($disc,2);@endphp
                                        @php $vatAmount = number_format($pVat,2);@endphp
                                        <td>£{{ $discountAmount }}</td>
                                    </tr>
                                    <?php } ?>
                                @endif

                                @if (Auth::user()->type == 'wholesaler')
                                    <tr>

                                        <td>Discount</td>
                                        @php $discountAmount = number_format(($originalPrice - $cartSum),2);@endphp
                                        <td>£{{ $discountAmount }}</td>
                                    </tr>
                                    <tr>

                                        <td>Vat</td>

                                        @php $vatAmount = number_format(($subTotal*$vatCharges)/100,2);@endphp
                                        <td>£{{ number_format(($subTotal * $vatCharges) / 100, 2) }}</td>

                                        @php $subTotal=number_format($subTotal+($subTotal*$vatCharges)/100,2) @endphp

                                    </tr>
                                @endif
                                <tr>
                                    <td>Total</td>
                                    <td class="price-total">£{{ $subTotal }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if (Auth::user()->type == 'wholesaler' || Auth::user()->type == 'dropshipper')
                            <?php $totalAmountWallet = getWholsellerDataWallet(Auth::user()->id);
                            $subtotals = $subTotal;
                            ?>

                            <div class="form-box">
                                <button type="button"
                                    style=" 	margin-top: 40px;
   background: darkslateblue;
    position: relative;
    display: inline-block;
    width: 100%;
    min-height: 25px;
    min-width: 150px;"
                                    class="disabled disableSection paywithwallet"
                                    onclick="payWithWallet({{ filter_var($subtotals, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) }},{{ $totalAmountWallet }})">Pay
                                    From
                                    wallet({{ number_format($totalAmountWallet, 2, '.', '') }})</button>
                            </div>
                        @endif
                        <div class="btn-order">
                            <!-- <a href="#" class="order" title="">Place Order</a> -->
                            <div id="paypal-button-container"></div>
                            {{-- <div id="paypal-button"></div> --}}
                        </div><!-- /.btn-order -->
                    </div><!-- /.cart-totals style2 -->
                </div><!-- /.col-md-5 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-checkout -->

    <section class="flat-row flat-iconbox style5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="iconbox style1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{ asset('front_assets/images/icons/car.png') }}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Worldwide Shipping</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-lg-3 col-md-6 -->
                <div class="col-lg-3 col-md-6">
                    <div class="iconbox style1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{ asset('front_assets/images/icons/order.png') }}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Order Online Service</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-lg-3 col-md-6 -->
                <div class="col-lg-3 col-md-6">
                    <div class="iconbox style1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{ asset('front_assets/images/icons/payment.png') }}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Payment</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-lg-3 col-md-6 -->
                <div class="col-lg-3 col-md-6">
                    <div class="iconbox style1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{ asset('front_assets/images/icons/return.png') }}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Return 30 Days</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-lg-3 col-md-6 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-iconbox -->
@endsection


@section('scripts')
    {{-- live --}}
    <script
        src="https://www.paypal.com/sdk/js?client-id=AbFSkkSixS51Qe_69o4v1RVOvTeTAcFYW-d8SspuBFQWswkBsof5UsvNF6RGAFMLIoZn7Z4PEnYYhJei&currency=GBP">
    </script>
    {{-- sandbox --}}
    {{-- <script src="https://www.paypal.com/sdk/js?client-id=AbFSkkSixS51Qe_69o4v1RVOvTeTAcFYW-d8SspuBFQWswkBsof5UsvNF6RGAFMLIoZn7Z4PEnYYhJei&currency=GBP"></script> --}}

    <script type="text/javascript">
        var discountAmount = "{{ isset($discountAmount) ? @$discountAmount : 0 }}";
        var vatAmount = "{{ isset($vatAmount) ? @$vatAmount : 0 }}";
        $(document).ready(function() {
            // disable payment
            $("#paypal-button-container").addClass("disableSection");

            // save user data
            $('#myForm').validator().on('submit', function(e) {
                if (e.isDefaultPrevented()) {
                    // handle the invalid form...

                } else {

                    e.preventDefault();
                    // everything looks good!
                    var formData = $('#myForm').serialize();
                    show_loader();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ url('save-user-info') }}',
                        method: "post",
                        data: {
                            formData: formData
                        },
                        success: function(response) {
                            if (response) {
                                if (response.status) {
                                    // enable payment
                                    $("#paypal-button-container").removeClass("disableSection");
                                    $(".paywithwallet").removeClass("disableSection");
                                    $("#myForm").addClass("disableSection");
                                    toastr.success(response.message);
                                    hide_loader();
                                }
                            }
                        }
                    });
                }
            })

            // paypal starts
            if ("{{ Auth::id() }}") {
                PayPalPayment();
                /*setTimeout(function(){
                	PayPalPayment();
                }, 3000);*/
            }
            // paypal starts

            // get cart
            getCartDetails();
        });

        function getCartDetails() {
            $.ajax({
                url: '{{ url('cart-details') }}',
                method: "post",
                dataType: "html",
                success: function(response) {
                    if (response) {
                        $('#cart_details').html(response);
                    }
                }
            });
        }

        function PayPalPayment() {
            // var amount = "{{ Auth::id() ? Cart::session(Auth::id())->getSubTotal() ?? 0 : 0 }}";
            var amount = "{{ $subTotal }}";
            paypal.Buttons({
                style: {
                    color: 'blue',
                    shape: 'pill',
                    label: 'pay',
                    height: 40
                },
                createOrder: function(data, actions) {

                    // Set up the transaction
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: amount,
                                currency: 'GBP'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {

                        var trans_id = details.id;
                        var payer = details.payer.email_address;
                        var payer_name = details.purchase_units[0].shipping.name;
                        var payee = details.purchase_units[0].payee.email_address;
                        var merchant_id = details.purchase_units[0].payee.merchant_id;
                        var amount = details.purchase_units[0].amount.value;
                        var currency = details.purchase_units[0].amount.currency_code;
                        var shipping_address = details.purchase_units[0].shipping.address.admin_area_1;

                        var allData = {
                            trans_id: trans_id,
                            payer: payer,
                            payer_name: payer_name,
                            payee: payee,
                            merchant_id: merchant_id,
                            amount: amount,
                            currency: currency,
                            shipping_address: shipping_address,
                            discount: discountAmount,
                            vat_amount: vatAmount
                        };


                        // alert('Transaction completed by ' + details.payer.name.given_name);
                        // Call your server to save the transaction
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ url('/paypal-transaction-complete') }}',
                            method: "post",
                            data: allData,
                            success: function(response) {
                                console.log(response);
                                if (response.status) {
                                    window.location.href = "{{ url('my-orders') }}";
                                }
                            },
                            error: function(request, status, error) {
                                alert(request.responseText);
                            }
                        });
                    });
                },
                onCancel: function(data, actions) {
                    toastr.error("You have cancelled the payment");
                    return false;
                }
            }).render('#paypal-button-container');
        }

        function payWithWallet(subtotal, available) {
            if (available > subtotal) {
                $.ajax({
                    url: '{{ url('/paywith-wallet') }}',
                    method: "post",
                    data: {
                        amount: subtotal,
                        discount: discountAmount,
                        vat_amount: vatAmount
                    },
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "{{ url('my-orders') }}";
                        }
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                });
            } else {
                toastr.error("Your wallet amount is less than cart amount please pay with alternate payment method");
                return false;
            }
        }
    </script>
@endsection
