@extends('layouts.app')

@section('content')

    <section class="flat-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs">
                        <!-- <li class="trail-item">
                            <a href="#" title="">Home</a>
                            <span><img src="{{asset('front_assets/images/icons/arrow-right.png')}}" alt=""></span>
                        </li>
                        <li class="trail-item">
                            <a href="#" title="">Shop</a>
                            <span><img src="{{asset('front_assets/images/icons/arrow-right.png')}}" alt=""></span>
                        </li>
                        <li class="trail-end">
                            <a href="#" title="">Smartphones</a>
                        </li> -->
                    </ul><!-- /.breacrumbs -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-breadcrumb -->

    <main id="shop">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="sidebar ">

                        <div class="widget widget-categories">
                            <div class="widget-title">
                                <h3>Categories<span></span></h3>
                            </div>

                            <ul class="cat-list style1 widget-content">
                                @forelse(getCategories() as $category)
                                    <li>
                                        <span class="categoryType" id="{{$category->id.'-'.$category->name}}">{{$category->name}}</span>
                                        @if($category->subcategories->count()>0)
                                            <ul class="cat-child">
                                                @foreach($category->subcategories as $subcategory)
                                                    <li>
                                                        <a class="categoryType" id="{{$subcategory->id.'-'.$subcategory->name}}"  href="javascript:void(0)" title="">{{$subcategory->name}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @empty
                                    <li>No Records Found</li>
                                @endforelse
                            </ul>
                        </div>
                        @include('products.search_input_view')

                        {{--<div class="widget widget-products">
                            <div class="widget-title">
                                <h3>Best Seller<span></span></h3>
                            </div>
                            <ul class="product-list widget-content">
                                <li>
                                    <div class="img-product">
                                        <a href="#" title="">
                                            <img src="{{asset('front_assets/images/blog/14.jpg')}}" alt="">
                                        </a>
                                    </div>
                                    <div class="info-product">
                                        <div class="name">
                                            <a href="#" title="">Razer RZ02-01071 <br/>500-R3M1</a>
                                        </div>
                                        <div class="queue">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </div>
                                        <div class="price">
                                            <span class="sale">$50.00</span>
                                            <span class="regular">$2,999.00</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="img-product">
                                        <a href="#" title="">
                                            <img src="{{asset('front_assets/images/blog/13.jpg')}}" alt="">
                                        </a>
                                    </div>
                                    <div class="info-product">
                                        <div class="name">
                                            <a href="#" title="">Notebook Black Spire <br/>V Nitro VN7-591G</a>
                                        </div>
                                        <div class="queue">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </div>
                                        <div class="price">
                                            <span class="sale">$24.00</span>
                                            <span class="regular">$2,999.00</span>
                                        </div>
                                    </div>
                                </li>
                                <li>

                                    <div class="img-product">
                                        <a href="#" title="">
                                            <img src="{{asset('front_assets/images/blog/12.jpg')}}" alt="">
                                        </a>
                                    </div>
                                    <div class="info-product">
                                        <div class="name">
                                            <a href="#" title="">Apple iPad Mini <br/>G2356</a>
                                        </div>
                                        <div class="queue">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </div>
                                        <div class="price">
                                            <span class="sale">$90.00</span>
                                            <span class="regular">$2,999.00</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="widget widget-banner">
                            <div class="banner-box">
                                <div class="inner-box">
                                    <a href="#" title="">
                                        <img src="{{asset('front_assets/images/banner_boxes/06.png')}}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>--}}
                    </div><!-- /.sidebar -->
                </div><!-- /.col-lg-3 col-md-4 -->
                <div class="col-lg-9 col-md-8">
                    <div class="main-shop">
                        <!-- <div class="slider owl-carousel-16">
                            <div class="slider-item style9">
                                <div class="item-text">
                                    <div class="header-item">
                                        <p>You can build the banner for other category</p>
                                        <h2 class="name">Shop Banner</h2>
                                    </div>
                                </div>
                                <div class="item-image">
                                    <img src="{{asset('front_assets/images/banner_boxes/07.png')}}" alt="">
                                </div>
                                <div class="clearfix"></div>
                            </div>/.slider-item style9
                            <div class="slider-item style9">
                                <div class="item-text">
                                    <div class="header-item">
                                        <p>You can build the banner for other category</p>
                                        <h2 class="name">Shop Banner</h2>
                                    </div>
                                </div>
                                <div class="item-image">
                                    <img src="{{asset('front_assets/images/banner_boxes/07.png')}}" alt="">
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </div> -->
                        <!-- /.slider -->
                        <div class="wrap-imagebox">
                            <div class="flat-row-title">
                                <h3 id="categoryName">All Categories</h3>
                                {{--<span>
										Showing 1–15 of 20 results
									</span>--}}
                                <div class="clearfix"></div>
                            </div>
                            <div class="sort-product">
                                <ul class="icons">
                                    <li class="grid" onclick="changeViewType(this)">
                                        <img src="{{asset('front_assets/images/icons/list-1.png')}}" alt="">
                                    </li>
                                    <li class="list" onclick="changeViewType(this)">
                                        <img src="{{asset('front_assets/images/icons/list-2.png')}}" alt="">
                                    </li>
                                </ul>
                                <div class="sort">
                                    <input type="hidden" name="page_records" value="2" class="page_records">
                                    <input type="hidden" name="page_order" value="desc" class="page_order">
                                    <div class="popularity">
                                        <select name="order" class="order">
                                            <optgroup label="Age">
                                                <option value="desc">Newest First</option>
                                                <option value="asc">Oldest First</option>
                                            </optgroup>
                                            <optgroup label="Price">
                                                <option value="lowest">Lowest Price First</option>
                                                <option value="highest">Highest Price First</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="showed">
                                        <select name="per_page_records" class="per_page_records">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <!-- hidden fields -->
                            <input type="hidden" id="page_records" value="10"/>
                            <!-- partial records -->
                            <div id="partial_records"></div>

                        </div><!-- /.wrap-imagebox -->

                    </div><!-- /.main-shop -->
                </div><!-- /.col-lg-9 col-md-8 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </main><!-- /#shop -->

    <section class="flat-imagebox mv style4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flat-row-title">
                        <h3>Recent Products</h3>
                    </div>
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel-3 style3">
                        @forelse($recent_products as $product)
                        <div class="imagebox style4">
                            <div class="box-image prodt_img">
                                <a href="{{ url('products/'.Hashids::encode($product->id)) }}" title="">
                                    <img src="{{ $product->default_image_url }}" alt="{{ $product->name }}">
                                </a>
                            </div><!-- /.box-image -->
                            <div class="box-content">
                                @if($product->category_products->count()>0)
                                    <div class="cat-name">
                                        <a href="{{ url('products/'.Hashids::encode($product->id)) }}" title="{{ $product->name }}">{{ $product->category_products[0]->category->name }}</a>
                                    </div>
                                @endif
                                <div class="product-name">
                                    <a href="{{ url('products/'.Hashids::encode($product->id)) }}" title="">{{ $product->name }}</a>
                                </div>
                                <div class="price">
                                    <span class="sale">£{{ getDiscountedPrice($product) }}</span>
                                    @if($product->discount_type>0)
                                        <span class="regular">£{{ number_format($product->price,2) }}</span>
                                    @endif
                                </div>
                            </div><!-- /.box-content -->
                        </div>
                        @empty
                        <div>
                            No Records Found
                        </div>
                        @endforelse
                    </div><!-- /.owl-carousel-3 -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-imagebox style4 -->

@endsection

@section('scripts')
<script type="text/javascript">

    function make_params_checked(val, current_url){
        val = val.replace(" ", "");
        if(current_url.indexOf(val)){
            /*$('.'+val).prop('checked', true);
            $('.'+val).closest(".moreOptons_box").addClass("checked");*/

        }
    }

    $(document).ready(function() {

        const url = "{{ url('get-products') }}";
        let current_url = window.location.href;

        let page_number = getParams('page', current_url);
        if(!page_number){
            page_number = 1;
        }

        // getting records from url
        let records =  getParams('records', current_url);
        if(!records){
            records = 50;
        }
        $(".per_page_records").val(records);

        // getting order from url
        let ordering=  getParams('order', current_url);
        if(!ordering){
            ordering = 'desc';
        }
        $('.order').val(ordering);

        // append category and view type in url
        let check_view=  getParams('category');
        if(!check_view){
            current_url = current_url+'?category=all&view-type=grid';
        }

        // check view type on window load
        let check_view_type=  getParams('view-type');
        if(check_view_type=='list'){
            $('.list').addClass('active');
            $('.grid').removeClass('active');
        }

        // more options url
        let more_options_url = '';
        if((current_url.indexOf('filter') != -1)){
            more_options_url = (current_url).split('page='+page_number).pop();
        }

        // on browser back button
        window.onpopstate = function() {
        	browserBackBtnHandler(url);
        };

        // manage url state with ajax url
        window.history.pushState("", "", current_url);

        getProducts(url+'?page='+page_number+more_options_url, records, ordering);

        // chagne records per page
        $(document).on('change', '.per_page_records', function (e) {
            e.preventDefault();
            let records_per_page = $(this).val();
            $('.page_records').val(records_per_page);

            current_url     = window.location.href;

            recordsPerPage(records_per_page, url, current_url, more_options_url, ordering);
        });

        // change sort by
        $(document).on('change', '.order', function (e) {
            e.preventDefault();
            let new_order = $(this).val();
            $('.page_order').val(new_order);

            current_url     = window.location.href;

            // getting records from url
            records =  getParams('records', current_url);
            if(!records){
                records = 10;
            }

            orderChange(new_order, url, current_url, more_options_url, ordering, page_number, records);
        });

        // simple trick to do ajax pagination
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var current_url = window.location.href;

            pageClick(url, current_url, more_options_url, 'products');
        });

        $('body').on('click', '.categoryType', function(e) {
            e.preventDefault();
            let category_id = $(this).attr('id');
            current_url     = window.location.href;
            let selectedCategoryName = (category_id).split('-').pop();
            category_id = category_id.replace(/ & /g, "-");
            category_id = category_id.replace(/, /g, "-");
            category_id = category_id.replace(/ /g, "-");
            category_id = category_id.replace("(", "");
            category_id = category_id.replace(")", "");
            $('#categoryName').text(selectedCategoryName);

            let category_param  = getParams('category');
            let updated_url = current_url.replace("category="+category_param, "category="+category_id);
            window.history.pushState("", "", updated_url);

            getCategoryProducts(category_id, url, current_url, more_options_url, ordering, page_number, records);
        });

        let min_price = getParams('min_price', current_url);
        if(!min_price){
            min_price = 0;
        }

        let max_price = getParams('max_price', current_url);
        if(!max_price){
            max_price = 500;
        }

        $('#min_price').val(min_price);
        $('#max_price').val(max_price);
        $('.show_range').text("£" + (parseInt(min_price).toFixed(2)) + " - £" + (parseInt(max_price).toFixed(2)));
        $( "#slider-range2" ).slider({
            range: true,
            min: 0,
            max: 1000,
            step: 5,
            values: [ min_price, max_price ],
            slide: function( event, ui ) {
                // $( "#amount2" ).val( "$" + addCommas(ui.values[ 0 ].toFixed(2)) + " - $" + addCommas(ui.values[ 1 ].toFixed(2)) );
                $('.show_range').text("£" + (ui.values[ 0 ].toFixed(2)) + " - £" + (ui.values[ 1 ].toFixed(2)));
                $("#min_price").val((ui.values[ 0 ]));
                $("#max_price").val((ui.values[ 1 ]));
            }
        });
    });

    function addRemoveWishlist(product_id){
        var url = "{{ url('get-products') }}";
        var current_url     = window.location.href;
        var post_url        = "{{url('add-to-wishlist')}}";
        var current_url_split = current_url.split('?');
        var category_id = getParams('category_id', current_url);
        var page_number = getParams('page', current_url);
        if(!page_number){
            page_number = 1;
        }
        var records =  getParams('records', current_url);
        if(!records){
            records = 10;
        }
        var ordering=  getParams('order', current_url);
        if(!ordering){
            ordering = 'desc';
        }

        if(current_url_split.length>1){
            url = url+'?'+current_url_split[1];
        }else{
            url = url+'?page='+page_number+'&category_id='+category_id;
        }

        var login_check = "{{Auth::check()}}";

        favoriteRequest(product_id, url, records, ordering, login_check, post_url);
    }

    // change view type in url
    function changeViewType(obj){
        let current_url     = window.location.href;
        if ($(obj).hasClass("list")) {

            $('.grid_pagination').addClass('hideImp');
            updated_url = current_url.replace("view-type=grid", "view-type=list");
        }else{
            $('.grid_section').removeClass('hide');
            $('.grid_section').removeClass('show');
            $('.grid_section').addClass('flex');
            $('.grid_pagination').removeClass('hide');
            $('.grid_pagination').addClass('showImp');
            updated_url = current_url.replace("view-type=list", "view-type=grid");
        }
        window.history.pushState("", "", updated_url);
    }

</script>
@endsection
