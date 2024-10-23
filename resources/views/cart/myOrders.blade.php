@extends('layouts.app')

@section('content')

<style type="text/css">
    .left{
        float: left;
        width:90%;
    }
    .right{
        float: right;
    }
</style>

<div class="popup-newsletter" style="display: none">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="popup">
                    <span></span>
                    <div class="popup-text">
                        <h2>Transaction Details</h2>
                        <div class="model_row_head"><h3>Payer Info:</h3> </div>
                        <div><p class="trip_head left">Payer Name: </p> <p class="right payer_name"></p></div>
                        <div><p class="trip_head left">Payer Email: </p> <p class="right payer_email"></p></div>
                        <div><p class="trip_head left">Shippping Address: </p> <p class="right shipping_address"></p></div>

                        <div class="model_row_head"><h3>Payment Info:</h3> </div>


                        <div><p class="trip_head left">Transaction No: </p> <p class="right trans_number"></p></div>
                        <div><p class="trip_head left">Total Deposit: </p> <p class="right amount"></p></div>
                        <div><p class="trip_head left">Payment Via: </p> <p class="right pay_via"></p></div>
                        <div><p class="trip_head left">Payment Status: </p> <p class="right payment_status"></p></div>
                        <!-- <p class="subscribe">Subscribe to the newsletter to receive updates about new products.</p>
                        <div class="form-popup">

                            <div class="checkbox">
                                <label for="popup-not-show">Don't show this popup again</label>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="popup-image">
                        <p>adsfasd</p>
                    </div> -->
                </div><!-- /.popup -->
            </div><!-- /.col-sm-8 -->

        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.popup-newsletter -->

<section class="flat-wishlist">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="wishlist">
                    <div class="title">
                        <h3>My Orders</h3>
                    </div>
                    <div id="partial_records"></div>
                </div><!-- /.wishlist -->
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.flat-wishlish -->

<section class="flat-row flat-iconbox style2">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="iconbox style1">
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
            </div><!-- /.col-md-6 col-lg-3 -->
            <div class="col-md-6 col-lg-3">
                <div class="iconbox style1">
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
            </div><!-- /.col-md-6 col-lg-3 -->
            <div class="col-md-6 col-lg-3">
                <div class="iconbox style1">
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
            </div><!-- /.col-md-6 col-lg-3 -->
            <div class="col-md-6 col-lg-3">
                <div class="iconbox style1">
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
            </div><!-- /.col-md-6 col-lg-3 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.flat-iconbox -->

@endsection

@section('scripts')
<script type="text/javascript">

    $(document).ready(function() {

        var url = "{{ url('get-my-orders') }}";
        getMyOrders(url, 500);
    });

    function transactionDetails(id){

        var popup = function() {
            $('.popup-newsletter').each( function() {
                $(this).closest('.boxed').children('.overlay').css({
                    opacity: '1',
                    display: 'block',
                    zIndex: '89999'
                });
                $(".popup span" ).on('click', function() {
                    $(this).closest('.popup-newsletter').hide(400);
                    $(this).closest('.boxed').children('.overlay').css({
                        opacity: '0',
                        display: 'none',
                         zIndex: '909'
                    });
                });
            });
        };

        show_loader();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ url('get-transaction-details') }}',
            method: "post",
            data: {id: id},
            success: function (response) {
                console.log(response.data);
                if(response.status){
                    $('.payer_name').text(response.data.payer_name.full_name);
                    $('.payer_email').text(response.data.payer);
                    $('.shipping_address').text(response.data.shipping_address);
                    $('.trans_number').text(response.data.trans_id);
                    $('.amount').text(response.data.amount + ' ' + response.data.currency);
                    $('.pay_via').text('PayPal');
                    $('.payment_status').text('Verified');
                    hide_loader();
                }
            }
        });

        popup();
        $('.popup-newsletter').show();
    }

</script>
@endsection
