@extends('layouts.app')

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

<section class="flat-shop-cart">
	<div class="container">
		<div id="cart_details"></div>
	</div><!-- /.container -->
</section><!-- /.flat-shop-cart -->

<section class="flat-row flat-iconbox style3">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6">
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
			</div><!-- /.col-lg-3 col-md-6 -->
			<div class="col-lg-3 col-md-6">
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
			</div><!-- /.col-lg-3 col-md-6 -->
			<div class="col-lg-3 col-md-6">
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
			</div><!-- /.col-lg-3 col-md-6 -->
			<div class="col-lg-3 col-md-6">
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
			</div><!-- /.col-lg-3 col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section><!-- /.flat-iconbox -->

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        getCartDetails();


    });

    function getCartDetails(){
        $.ajax({
            url: '{{ url('cart-details') }}',
            method: "post",
            dataType: "html",
            success: function (response) {
                if(response){
                    $('#cart_details').html(response);
                    // $('#cart_details').LoadingOverlay('hide');
                }
            }
        });
    }
</script>
@endsection
