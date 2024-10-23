@extends('layouts.app')

@section('content')
<!-- hidden fields -->
<input type="hidden" id="pagination_limit" value="10"/>
<!-- hidden fields -->

<section class="flat-breadcrumb">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumbs">
					<li class="trail-item">
						<a href="{{url('/')}}" title="">Home</a>
						<span><img src="{{asset('front_assets/images/icons/arrow-right.png')}}" alt=""></span>
					</li>
					<li class="trail-end">
						<a href="javascript:void(0)" title="">Top Categories</a>
					</li>
				</ul><!-- /.breacrumbs -->
			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section><!-- /.flat-breadcrumb -->

<section class="flat-slider style2">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="slider owl-carousel style2">
					<div class="slider-item style3">
						<div class="item-text">
							<div class="header-item">
								<p>You can build the banner for other category</p>
								<h2 class="name"><span>SHOP BANNER</span></h2>
							</div>
						</div>
						<div class="item-image">
							<img src="{{asset('front_assets/images/banner_boxes/07.png')}}" alt="">
						</div>
						<div class="clearfix"></div>
					</div><!-- /.slider-item style3 -->
					<div class="slider-item style3">
						<div class="item-text">
							<div class="header-item">
								<p>You can build the banner for other category</p>
								<h2 class="name"><span>SHOP BANNER</span></h2>
							</div>
						</div>
						<div class="item-image">
							<img src="{{asset('front_assets/images/banner_boxes/07.png')}}" alt="">
						</div>
						<div class="clearfix"></div>
					</div><!-- /.slider-item style3 -->
				</div><!-- /.slider -->
			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section><!-- /.flat-slider style2 -->

<section class="flat-row flat-imagebox">
	<div id="partial_records"></div>
</section><!-- /.flat-imagebox style1 -->

<section class="flat-row flat-highlights style1">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="flat-row-title">
					<h3>Bestsellers</h3>
				</div><!-- /.flat-row-title -->
				<ul class="product-list style1">
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/10.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Razer RZ02-01071500-R3M1</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£50.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div><!-- /.info-product -->
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/9.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Apple iPad Mini G2356</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£24.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div><!-- /.info-product -->
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/8.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Beats Snarkitecture Headphones</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£90.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div><!-- /.info-product -->
						<div class="clearfix"></div>
					</li>
				</ul><!-- /.product-list style1 -->
			</div><!-- /.col-md-4 -->
			<div class="col-md-4">
				<div class="flat-row-title">
					<h3>Featured</h3>
				</div><!-- /.flat-row-title -->
				<ul class="product-list style1">
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/3.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Razer RZ02-01071500-R3M1</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£50.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/2.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Apple iPad Mini G2356</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£24.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/1.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Beats Snarkitecture Headphones</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£90.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul><!-- /.product-list style1 -->
			</div><!-- /.col-md-4 -->
			<div class="col-md-4">
				<div class="flat-row-title">
					<h3>Hot Sale</h3>
				</div><!-- /.flat-row-title -->
				<ul class="product-list style1">
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/19.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Razer RZ02-01071500-R3M1</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£50.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/11.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Apple iPad Mini G2356</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£24.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="img-product">
							<a href="#" title="">
								<img src="{{asset('front_assets/images/product/highlights/20.jpg')}}" alt="">
							</a>
						</div>
						<div class="info-product">
							<div class="name">
								<a href="#" title="">Beats Snarkitecture Headphones</a>
							</div>
							<div class="queue">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</div>
							<div class="price">
								<span class="sale">£90.00</span>
								<span class="regular">£2,999.00</span>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul><!-- /.product-list style1 -->
			</div><!-- /.col-md-4 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section><!-- /.flat-highlights -->

@section('scripts')
    <script>

        $(function () {

        	var url = "{{ url('categories/get-categories') }}";
        	var current_url = window.location.href;

	        var page_number = getParams('page', current_url);
	        if(!page_number){
	            page_number = 1;
	        }
        	// getting records from url
	        var records =  getParams('records', current_url);
	        if(!records){
	            records = 1;
	        }

        	// on browser back button
	        window.onpopstate = function() {

	            // $("body").LoadingOverlay("show");
	            var current_url = window.location.href;
	            var page_number = getParams('page', current_url);
	            if(!page_number){
		            page_number = 1;
		        }

	            // set timeout to get actual page url otherwise page number missing
	            setTimeout(function(){

	                getCategories(url+'?page='+page_number, records);

	            }, 1000);
	            $("body").LoadingOverlay("hide");
	        };

            getCategories(url+'?page='+page_number, records);

            $('body').on('click', '.pagination a', function(e) {
	            e.preventDefault();

	            var url = $(this).attr('href');
	            // alert(url);return false;

	            var current_url = window.location.href;
	            // alert(current_url);return false;

	            pageClick(url, current_url, 'categories');
	        });
        });
    </script>
@endsection

@endsection
