@extends('layouts.app')

@section('content')

<!-- hidden fields -->
<input type="hidden" id="page_records" value="1"/>
<!-- hidden fields -->

<section class="flat-wishlist">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="wishlist">
                    <div class="title">
                        <h3>My wishlist</h3>
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

        var url = "{{ url('get-wishlist') }}";
        getWishlist(url, 500);
    });

    function removeWishlist(product_id){

        var url = "{{ url('get-wishlist') }}";
        var token = "{{ csrf_token() }}";

        getWishlist(url, 500, token, product_id);
    }

</script>
@endsection
