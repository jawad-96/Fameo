@extends('layouts.app')

@section('content')
    <section class="flat-tracking background">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="order-tracking">
                        <div class="title">
                            <h3>Track Your Order</h3>
                            <p class="subscibe">
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br />Excepteur sint occaecat cupidatat non proident.
                            </p>
                        </div><!-- /.title -->
                        <div class="tracking-content">
                            <form class="checkout" id="myForm">
                                <div class="one-half order-id form-group">
                                    <label for="order-id">Order ID</label>
                                    <input class="form-control" type="text" id="order-id" name="orderId" placeholder="Order Id" required>
                                </div><!-- /.one-half order-id -->
                                <div class="one-half billing form-group">
                                    <label for="billing">Billing Email (Optional)</label>
                                    <input class="form-control" type="email" id="billing" name="email" placeholder="Found your order confirmation email">
                                </div><!-- /.one-half billing -->
                                <p id="orderStatus"></p>
                                <div class="btn-track">
                                    <button type="submit">Track</button>
                                </div><!-- /.container -->
                            </form><!-- /.form -->
                        </div><!-- /.tracking-content -->
                    </div><!-- /.order-tracking -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-tracking -->

    <section class="flat-row flat-iconbox style1 background">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="iconbox style1 v1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{asset('front_assets/images/icons/car.png')}}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Worldwide Shipping</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3">
                    <div class="iconbox style1 v1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{asset('front_assets/images/icons/order.png')}}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Order Online Service</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3">
                    <div class="iconbox style1 v1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{asset('front_assets/images/icons/payment.png')}}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Payment</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3">
                    <div class="iconbox style1 v1">
                        <div class="box-header">
                            <div class="image">
                                <img src="{{asset('front_assets/images/icons/return.png')}}" alt="">
                            </div>
                            <div class="box-title">
                                <h3>Return 30 Days</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.box-header -->
                    </div><!-- /.iconbox -->
                </div><!-- /.col-md-3 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-iconbox -->

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // save user data
            $('#myForm').validator().on('submit', function (e) {
                if (e.isDefaultPrevented()) {
                    return false;
                } else {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    // everything looks good!
                    show_loader();
                    var orderId = $('#order-id').val();
                    var email   = $('#billing').val();

                    $.ajax({
                        url: '{{ url('order-status') }}',
                        method: "post",
                        data: {orderId: orderId, email:email},
                        success: function (response) {
                            if(response){
                                if(response.status){
                                    $('#orderStatus').html(response.html);
                                    hide_loader();
                                }
                            }
                        }
                    });
                }
            })
        });
    </script>
@endsection
