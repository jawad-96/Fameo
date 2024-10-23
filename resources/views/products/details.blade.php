@extends('layouts.app')
@section('title')
    {{$product->meta_title??''}}
@endsection
@section('description')
    {{$product->meta_description??''}}
@endsection
@section('content')
    <section class="flat-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs">
                        <li class="trail-item">
                            <a href="#" title="">Home</a>
                            @if($product->category_products->count()>0)
                                <span><img src="{{asset('front_assets/images/icons/arrow-right.png')}}" alt=""></span>
                            @endif
                        </li>
                        @if($product->category_products->count()>0)
                            @if(@$product->category_products[0]->category->name)
                                <li class="trail-item">
                                    <a href="#" id="">{{ $product->category_products[0]->category->name }}</a>
                                    <span><img src="{{asset('front_assets/images/icons/arrow-right.png')}}" alt=""></span>
                                </li>
                            @endif
                            @if(@$product->category_products[1]->category->name)
                                <li class="trail-end">
                                    <a href="{{url('products?order=asc&page=1&category_id='.@$product->category_products[1]->category->id)}}" title="">{{ $product->category_products[1]->category->name }}</a>
                                </li>
                            @endif
                        @endif
                    </ul><!-- /.breacrumbs -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-breadcrumb -->

    <section class="flat-product-detail">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="flexslider">
                        <ul class="slides">
                            @foreach($images as $image)
                                <li data-thumb="{{ $image->image_thumb }}">
                                    <a href='#' id="zoom{{$loop->iteration}}" class='zoom'><img src="{{ $image->image_url }}" alt='' width='400' height='300' /></a>
                                </li>
                            @endforeach
                        </ul><!-- /.slides -->
                    </div><!-- /.flexslider -->
                </div><!-- /.col-md-6 -->
                <div class="col-md-6">
                    <div class="product-detail">
                        <div class="header-detail">
                            <h4 class="name title_name">{{ $product->name }}</h4>
                            @if($product->brand)
                                <div class="category"><b>Brand:</b> {{ $product->brand->name }}</div>
                            @endif
                            <div class="reviewed">
                                <!--   <div class="review">
                                       <div class="queue">
                                           <i class="fa fa-star" aria-hidden="true"></i>
                                           <i class="fa fa-star" aria-hidden="true"></i>
                                           <i class="fa fa-star" aria-hidden="true"></i>
                                           <i class="fa fa-star" aria-hidden="true"></i>
                                           <i class="fa fa-star" aria-hidden="true"></i>
                                       </div>
                                       <div class="text">
                                           <span>3 Reviews</span>
                                           <span class="add-review">Add Your Review</span>
                                       </div>
                                   </div>--><!-- /.review -->

                                <div class="status-product">
                                    Availablity

                                    <span style="background-color: {{(isset($product->store_products) and $product->store_products->count() >0 and $product->store_products[0]->quantity>0)?'green':'red'}};" id="available">{{(isset($product->store_products) and $product->store_products->count() >0 and $product->store_products[0]->quantity>0)?'In stock':'Out of stock'}}</span>

                                </div>
                            </div><!-- /.reviewed -->
                        </div><!-- /.header-detail -->
                        <div class="content-detail">

                            <div class="price">

                                @if(Auth::check() && Auth::guard('web')->check() && Auth::user()->type != 'retailer')
                                    @if(Auth::user()->type == 'wholesaler')
                                        <?php $totalPercent = ($product->cost * Auth::user()->mark_up/100);?>
                                        <span class="sale">£{{number_format(($product->cost + $totalPercent),2,'.','') }}</span>
                                    @else
                                        <span class="sale">£{{ $product->discountedPrice }}</span>
                                    @endif
                                @else
                                    @if($product->discount_type>0)
                                        <span class="regular">£{{ number_format($product->price,2,'.','') }}</span>
                                    @endif
                                    <span class="sale">£{{ $product->discountedPrice }}</span>
                                @endif

                            </div>
                            <div class="info-text">{!! $product->detail !!}</div>
                        <!-- <div class="product-id">
                                    SKU: <span class="id">{{ $product->sku }}</span>
                                </div> -->
                        </div><!-- /.content-detail -->
                        <div class="footer-detail">

                            @if($product->is_variants=="1")

                                <div class="quanlity-box">
                                    @foreach($product->product_attributes as $attr)
                                        <div class="colors variants variant-main-{{ $loop->iteration }}" style="display: {{ $loop->iteration==1?'inline-block':'none' }};">
                                            <select name="color" class="variant-{{ $loop->iteration }}" data-id="{{ $loop->iteration }}">
                                                <option value="0" prd-default={{($product->store_products->count() >0)?$product->store_products[0]->quantity:0}}>Select {{ $attr->variant->name }}</option>

                                                @foreach($product_variants as $key => $variant)
                                                    @if($variant->contains('variant_id',$attr->variant_id))
                                                        <option value="{{ $attr->variant_id }}">{{ $key }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="product-variant-{{ $loop->iteration }}" value="default">
                                        </div>
                                    @endforeach
                                    <input type="hidden" id="product_id" value="{{$product->id}}">
                                    <input type="hidden" id="total_variants" value="{{$product->product_attributes->count()}}">
                                </div>
                            @endif
                            @if(isset($product->store_products) and $product->store_products->count() >0 and @$product->store_products[0]->quantity>0)
                                <div class="quanlity-box" style="margin-top: 16px;">
                                    <div class="quanlity">
                                        <span class="btn-down"></span>
                                        <input type="text" class="qty" name="number" value="1" min="1" max="{{ $product->store_products[0]->quantity }}" placeholder="Quanlity">
                                        <span class="btn-up"></span>
                                    </div>
                                </div><!-- /.quanlity-box -->
                            @endif

                            <div class="box-cart style2">
                                <div class="btn-add-cart">
                                    <a href="javascript:void(0)" title="" data-id="{{$product->id}}" class="add-to-cart"   <?php if($product->store_products[0]->quantity > 0) {echo '';} else {echo 'disabled=disabled';}?>><img src="{{asset('front_assets/images/icons/add-cart.png')}}" alt="">Add to Cart</a>
                                </div>
                                <div class="compare-wishlist">
                                <!-- <a href="compare.html" class="compare" title=""><img src="{{asset('front_assets/images/icons/compare.png')}}" alt="">Compare</a> -->
                                    <a href="javascript:void(0)" class="wishlist" title="" onclick="addRemoveWishlist({{$product->id}})">
                                    <!-- {{$product->isFavorite?'Yes':'No'}} -->
                                        @if($product->isFavorite)
                                            <span class="yes">Yes</span>
                                            <span style="display: none;" class="no">No</span>
                                        @else
                                            <span class="no">No</span>
                                            <span style="display: none" class="yes">Yes</span>
                                        @endif
                                        <img src="{{asset('front_assets/images/icons/wishlist.png')}}" alt="">Wishlist</a>
                                </div>
                            </div><!-- /.box-cart -->
                            <div class="social-single">
                                <span>SHARE</span>
                                <div class="sharethis-inline-share-buttons"
                                     data-title="{{ $product->name }}"
                                     data-url="{{ url('products/'.Hashids::encode($product->id)) }}"
                                     data-image="{{ $product->default_image_url }}"
                                     data-description="{!! $product->detail !!}"
                                ></div>

                                <!-- <ul class="social-list style2">
                                    <li>
                                        <a href="#" title="">
                                            <i class="fa fa-facebook" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="">
                                            <i class="fa fa-twitter" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="">
                                            <i class="fa fa-instagram" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="">
                                            <i class="fa fa-pinterest" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="">
                                            <i class="fa fa-dribbble" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="">
                                            <i class="fa fa-google" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- /.social-list -->
                            </div><!-- /.social-single -->
                        </div><!-- /.footer-detail -->
                    </div><!-- /.product-detail -->
                </div><!-- /.col-md-6 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-product-detail -->

    <section class="flat-product-content">
        <ul class="product-detail-bar">
            <li class="active">Description</li>
            @if($product->tecnical_specs!="")
                <li>Tecnical Specs</li>
            @endif
            <li>Reviews</li>
        </ul><!-- /.product-detail-bar -->
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="description-text">
                        {!! $product->full_detail !!}
                    </div><!-- /.description-text -->
                </div><!-- /.col-md-6 -->
            </div><!-- /.row -->
            @if($product->tecnical_specs!="")
                <div class="row">
                    <div class="col-md-12">
                        <div class="tecnical-specs">
                            {!! $product->tecnical_specs !!}
                        </div><!-- /.tecnical-specs -->
                    </div><!-- /.col-md-12 -->
                </div><!-- /.row -->
            @endif
            <div class="row">
            {{--<div class="col-md-6">
                <div class="rating">
                    <div class="title">
                        Based on 3 reviews
                    </div>
                    <div class="score">
                        <div class="average-score">
                            <p class="numb">4.3</p>
                            <p class="text">Average score</p>
                        </div>
                        <div class="queue">
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                        </div>
                    </div><!-- /.score -->
                    <ul class="queue-box">
                        <li class="five-star">
                            <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                            <span class="numb-star">3</span>
                        </li><!-- /.five-star -->
                        <li class="four-star">
                            <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                            <span class="numb-star">4</span>
                        </li><!-- /.four-star -->
                        <li class="three-star">
                            <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                            <span class="numb-star">3</span>
                        </li><!-- /.three-star -->
                        <li class="two-star">
                            <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                            <span class="numb-star">2</span>
                        </li><!-- /.two-star -->
                        <li class="one-star">
                            <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
                            <span class="numb-star">0</span>
                        </li><!-- /.one-star -->
                    </ul><!-- /.queue-box -->
                </div><!-- /.rating -->
            </div>--}}<!-- /.col-md-6 -->
                <div class="col-md-6">
                    <div class="form-review">
                        <div class="title">
                            Add a review
                        </div>
                        <div class="your-rating queue">
                            {{--<span>Your Rating</span>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>--}}
                        </div>
                        <form action="#" method="get" accept-charset="utf-8">
                            <div class="review-form-name">
                                <input type="text" name="name-author" value="" placeholder="Name">
                            </div>
                            <div class="review-form-email">
                                <input type="text" name="email-author" value="" placeholder="Email">
                            </div>
                            <div class="review-form-comment">
                                <textarea name="review-text" placeholder="Your Name"></textarea>
                            </div>
                            <div class="btn-submit">
                                <button type="button" id="addreview">Add Review</button>
                            </div>
                        </form>
                    </div><!-- /.form-review -->
                </div><!-- /.col-md-6 -->
                <div class="col-md-12">
                    <ul class="review-list">


                    </ul><!-- /.review-list -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-product-content -->

@endsection

@section('scripts')
    <script type="text/javascript">
        var productID = "{{$product->id}}";
        var authType ="{{Auth::user()?Auth::user()->type:''}}";
        var mark_up =parseInt("{{Auth::user()?Auth::user()->mark_up:0}}");
        var varientData =parseInt("{{$product->is_variants?$product->is_variants:0}}");
        var product_attributes =parseInt("{{$product->product_attributes?$product->product_attributes->count():0}}");
        var countAttributes = [];
         console.log(product_attributes);
        $(document).ready(function() {
            getProductReviews(productID);
            $("#addreview").on("click",function(){

                if($("textarea[name='review-text']").val() !='' && $("input[name='name-author']").val() !='' ) {
                    $.ajax({
                        url: '{{ url('add-product-review') }}',
                        method: "POST",
                        data: {
                            product_id: productID,
                            name: $("input[name='name-author']").val(),
                            email: $("input[name='email-author']").val(),
                            description: $("textarea[name='review-text']").val()
                        },
                        success: function (response) {

                            toastr.success(response.message);
                            getProductReviews(productID);
                        }
                    });
                }else{
                    toastr.error("Please Provide Name and Description");
                }
            })
            $(".variants select").on("change",function(){

                var id = $(this).data('id');
                var product_id = $('#product_id').val();
                var variant_id = $(this).val();
                var val = $(this).find('option:selected').text();
                $("#product-variant-"+id).val(val);

                show_loader();
                if(id==1 && variant_id>0){
                    countAttributes = countAttributes.filter(item => item.id !== id);
                    countAttributes.push({id:id});
                    var child_variant_id = $("#product-variant-2").val();
                    var variantQuantity = 0;
                    $.ajax({
                        url: '{{ url('get-product-variants') }}',
                        method: "POST",
                        data: {id: id, product_id:product_id, child_variant_id:child_variant_id, variant_id:variant_id, val: val},
                        success: function (response) {
                            hide_loader();
                            if(variant_id==0){
                                $(".variant-main-2").hide();
                            }
                            var variants = response.variants;
                            if(variants.length>0){
                                $(".variant-main-2").show();
                                $(".variant-main-2 select").html('<option value="0">Select '+variants[0].variant.name+'</option>');
                                for (x in variants) {
                                    $(".variant-main-2 select").append('<option value='+variants[x].variant_id+'>'+variants[x].name+'</option>');
                                }
                            }
                            activeVariantProduct(response.product);
                        }
                    });
                }else if(id==2 && variant_id>0){
                    countAttributes = countAttributes.filter(item => item.id !== id);
                    countAttributes.push({id:id});
                    var parent_variant_id = $("#product-variant-1").val();
                    $.ajax({
                        url: '{{ url('get-product-variants') }}',
                        method: "POST",
                        data: {id: id, product_id:product_id, parent_variant_id:parent_variant_id, variant_id:variant_id, val: val},
                        success: function (response) {
                            hide_loader();
                            activeVariantProduct(response.product);
                        }
                    });
                }else {

                    var prdQuantity = $(this).find('option:selected').attr('prd-default');
                    setProductQuantity(prdQuantity);
                   
                    hide_loader();
                }


            });

            function activeVariantProduct(product){

                if(typeof(product) != "undefined" && product !== null) {
                    $(".title_name").text(product.name);
                    $(".add-to-cart").attr("data-id",product.id);
                    $(".wishlist").attr("onclick","addRemoveWishlist("+product.id+")");

                    if(product.is_variants==1){
                        var price = product.price;

                        $(".regular").text('£'+parseFloat(price).toFixed(2));
                        if(product.discount_type=="1"){
                            var discount = ((parseInt(price)*product.discount)/100);
                            price = parseFloat(price)-discount;
                        }else if(product.discount_type=="2"){
                            price = parseInt(price)-product.discount;
                        }
                    }else{
                        var price =0;

                        var actual_price = product.discountedPrice;
                        if(product.is_main_price==1){
                            price = product.product.price;
                        }else{
                            price = product.price;
                        }
                        if(authType !='' && authType =='wholesaler'){

                        var percent =( mark_up/100);
                        percent = product.cost * percent;
                            var productCost = parseFloat(product.cost);
                            price = parseFloat(percent) + productCost;

                        }else if(authType !='' && authType =='dropshipper'){
                            price = product.discountedPrice;
                        }else{



                            if(product.product.discount_type ==="1"){
                                var discount = ((parseFloat(price)*parseInt(product.product.discount))/100);

                                price = parseFloat(price)-discount;
                            }else if(product.product.discount_type=="2"){
                                price = parseInt(price)-product.product.discount;
                            }
                        }

                        $(".regular").text('£'+parseFloat(actual_price).toFixed(2));
                        $(".sale").text('£'+parseFloat(price).toFixed(2));


                    }

                    $(".sale").text('£'+parseFloat(price).toFixed(2));

                    $(".flex-control-thumbs li").each(function(){
                        if($(this).find('img').attr('src')==product.image_thumb)
                            $(this).find('img').click();
                    });
                    
                    console.log('product.store_products.length '+product.store_products.length);
                    
                    if(product.store_products.length>0){
                        setProductQuantity(product.store_products[0].quantity);
                    }else{
                        setProductQuantity(0);
                    }

                }
            }

            //update cart
            $(".add-to-cart").click(function (e) {
                e.preventDefault();
                if($(this).attr('disabled') =='disabled'){
                    return false;
                }

                if(product_attributes !== countAttributes.length){
                    error_message('Select Variant to add to cart');
                    return  false
                }
                var id = $(this).attr('data-id');
                var qty = $('.qty').val();
                // show_loader();
                $.ajax({
                    url: '{{ url('cart-add') }}',
                    method: "POST",
                    data: {id: id, quantity: qty},
                    success: function (response) {
                        if(response.status){
                            $('#cartTotal').text(response.cartTotal);
                            $('#cartPrice').text('£'+response.cartPrice);
                            // hide_loader();
                            success_message("Item successfully added to your cart");
                        } else {
                            error_message(response.message);
                        }
                    }
                });
            });



        });

        function addRemoveWishlist(product_id){
            var url = "{{ url('products/get-products') }}";
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
                records = 12;
            }
            var ordering=  getParams('order', current_url);
            if(!ordering){
                ordering = 'asc';
            }

            if(current_url_split.length>1){
                url = url+'?'+current_url_split[1];
            }else{
                url = url+'?page='+page_number+'&category_id='+category_id;
            }

            var login_check = "{{Auth::check()}}";

            favoriteRequest(product_id, url, records, ordering, login_check, post_url, true);
        }

        function getProductReviews(id) {

            $.ajax({
                url: '{{ url('get-product-review') }}',
                method: "POST",
                data: {
                    product_id: id,

                },
                success: function (response) {

                    $(".review-list li").remove();
                    if(response.data.length>0){
                        var html = '';

                        $.each(response.data,(index,value)=>{
                            html+='<li>\n' +
                                '                    <div class="review-metadata">\n' +
                                '                        <div class="name">\n' +
                                '                        '+value.name+' <span>'+value.dated+'</span>\n' +
                                '                    </div>\n'+
                                '                    </div><!-- /.review-metadata -->\n' +
                                '                    <div class="review-content">\n' +
                                '                        <p>\n' +
                                '                        '+value.description+'\n' +
                                '                    </p>\n' +
                                '                    </div><!-- /.review-content -->\n' +
                                '                    </li>'
                        });

                        $(".review-list").append(html);
                    }
                }
            });
        }
        
        function setProductQuantity(quantity) {
            if(quantity>0) {
                $("#available").text('In stock');
                $("#available").css('background-color', 'green');
                $(".qty").attr('max', quantity);
                $(".quanlity").show();
                $(".add-to-cart").attr('disabled',false);
            } else {
                $("#available").text('Out of stock');
                $("#available").css('background-color', 'red');
                $(".qty").attr('max', 0);
                $(".quanlity").hide();
                $(".add-to-cart").attr('disabled', true);
            }
        }
        
    </script>
@endsection
