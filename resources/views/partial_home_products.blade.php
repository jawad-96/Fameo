<div class="row new-arrival-products">

    <?php
    $user = Auth::user();
    foreach($products as $product){
    $slug = (!empty($product->slug))?$product->slug:Hashids::encode($product->id)

    ?>

    <div class="col-lg-3 col-sm-6">

        <div class="product-box">

            <div class="imagebox">



                {!! $product->sale_html !!}

                @if($product->new_arrivals=='1')
                    <span class="item-new">NEW</span>
                @endif

                <div class="box-image">

                    <a href="{{ url('products/'.$slug) }}" title="">

                        <img src="{{ $product->default_image_url }}" alt="{{ $product->name }}">

                    </a>

                </div><!-- /.box-image -->

                <div class="box-content">

                    @if($product->category_products->count()>0)

                        <div class="cat-name">

                            <a href="{{ url('products/'.$slug) }}" title="{{ $product->name }}">{{ $product->category_products[0]->category->name }}</a>

                        </div>

                    @endif

                    <div class="product-name">

                        <a href="{{ url('products/'.$slug) }}" title="{{ $product->name }}">{{ str_limit($product->name,28) }}<br />{{ $product->sku }}</a>

                    </div>

                    <div class="price">

                        @if(Auth::check() && Auth::guard('web')->check() && Auth::user()->type != 'retailer')
                            @if(Auth::user()->type == 'wholesaler')
                                <?php $totalPercent = ($product->cost * Auth::user()->mark_up/100);?>
                                <span class="sale">£{{number_format(($product->cost + $totalPercent),2,'.','')}}</span>
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

                </div><!-- /.box-content -->
                <?php $showcart = false;
                (isset( $product->store_products[0]->quantity) and $product->store_products[0]->quantity>0)?$showcart = true:$showcart=false;?>
                @if($showcart)

                <div class="box-bottom">

                    <div class="btn-add-cart">

                        <a href="javascript:void(0)" onclick="addToCart({{$product->id}})" title="" data-id="{{$product->id}}" class="add-to-cart"><img src="{{ asset('front_assets/images/icons/add-cart.png') }}" alt="">Add to Cart</a>

                    </div>

                    <div class="compare-wishlist">

                        <a href="javascript:void(0)" class="wishlist" title="" onclick="addRemoveWishlist({{$product->id}})">

                            {{$product->isFavorite?'Yes':'No'}}

                            <img src="{{asset('front_assets/images/icons/wishlist.png')}}" alt="">Wishlist

                        </a>

                    </div>

                </div><!-- /.box-bottom -->
                    @endif

            </div><!-- /.imagebox -->

        </div>

    </div><!-- /.col-lg-3 col-sm-6 -->

    <?php } ?>

</div><!-- /.row -->
