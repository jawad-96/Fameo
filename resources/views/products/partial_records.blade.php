<div class="tab-product">
    <div class="row sort-box grid_section">
        @forelse($products as $product)
            <?php $slug = (!empty($product->slug))?$product->slug:Hashids::encode($product->id); ?>
        <div class="col-lg-4 col-sm-6">
            <div class="product-box">
                <div class="imagebox">
                    @if($product->new_arrivals=='1')
                        <span class="item-new">NEW</span>
                    @endif
                    {{--@if($product->is_hot=='1')
                        <span class="item-sale">SALE</span>
                    @endif--}}
<!--                    @if(showDiscount($product))
                        <span class="item-sale">{{showDiscount($product)}}</span>
                    @endif-->

                    {!! $product->sale_html !!}

                    <div class="box-image owl-carousel-1">
                        <a href="{{ url('products/'.$slug) }}" title="">
                            <img src="{{ $product->default_image_url }}" alt="{{$product->name}}">
                        </a>


                    </div><!-- /.box-image -->
                    <div class="box-content">
                    @if($product->category_products->count()>0)
                        <div class="cat-name">
                            <a href="{{ url('products/'.$slug) }}" title="{{ $product->name }}">{{ $product->category_products[0]->category->name }}</a>
                        </div>
                    @endif
                        <div class="product-name">
                            <a href="{{ url('products/'.$slug) }}" title="{{ $product->name }}">{{ Str::limit($product->name,25) }}<br />{{ $product->sku }}</a>
                        </div>
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
                                    <span class="regular">£{{ number_format($product->price,2) }}</span>
                                @endif
                                <span class="sale">£{{ $product->discountedPrice }}</span>
                            @endif
                        </div>
                    </div><!-- /.box-content -->
                    <div class="box-bottom">
                        <div class="btn-add-cart">
                            <a href="javascript:void(0)" title="" onclick="addToCart({{$product->id}},{{$product->store_products[0]->quantity}})">
                                <img src="{{asset('front_assets/images/icons/add-cart.png')}}" alt="">Add to Cart
                            </a>
                        </div>
                        <div class="compare-wishlist">

                            <p>{{(isset($product->store_products) and $product->store_products->count() >0 and $product->store_products[0]->quantity>0)?'In Stock':'Out of Stock'}}</p>

                            <a href="javascript:void(0)" class="wishlist" title="" onclick="addRemoveWishlist({{$product->id}})">
                                {{$product->isFavorite?'Yes':'No'}}
                                <img src="{{asset('front_assets/images/icons/wishlist.png')}}" alt="">Wishlist
                            </a>
                        </div>
                    </div><!-- /.box-bottom -->
                </div><!-- /.imagebox -->
            </div>
        </div>
        @empty
        <div>
            No Records Found
        </div>
        @endforelse
    </div>
    <div class="blog-pagination  grid_pagination">
        <!-- <span>
            Showing 1–15 of 20 results
        </span> -->
        <div id="pagination">
            {{$products->render()}}
        </div>
        <div class="clearfix"></div>
    </div><!-- /.blog-pagination -->
    <div class="sort-box list_section">

        @forelse($products as $product)
        <div class="product-box style3">
            <div class="imagebox style1 v3">
                @if($product->new_arrivals=='1')
                    <span class="item-new">NEW</span>
                @endif
                {{--@if($product->is_hot=='1')
                    <span class="item-sale">SALE</span>
                @endif--}}
                @if(showDiscount($product))
                    <span class="item-sale">{{showDiscount($product)}}</span>
                @endif
                <div class="box-image">
                    <a href="{{ url('products/'.Hashids::encode($product->id)) }}" title="">
                        <img src="{{ $product->default_image_url }}" alt="{{$product->name}}">
                    </a>
                </div><!-- /.box-image -->
                <div class="box-content">
                @if($product->category_products->count()>0)
                    <div class="cat-name">
                        <a href="{{ url('products/'.Hashids::encode($product->id)) }}" title="{{ $product->name }}">{{ $product->category_products[0]->category->name }}</a>
                    </div>
                @endif
                    <div class="product-name">
                        <a href="{{ url('products/'.Hashids::encode($product->id)) }}" title="{{ $product->name }}">{{ Str::limit($product->name,25) }}<br />{{ $product->sku }}</a>
                    </div>
                    <div class="status">
                        Availablity:
                            @if(@$product->store_products[0]->quantity>0)
                                <span style="color: green;">In stock</span>
                            @else
                                <span>Out of stock</span>
                            @endif
                    </div>
                    <div class="info">
                        <p>
                            <?php if (strlen(strip_tags($product->full_detail)) > 100) {
                                $href= url('products/'.Hashids::encode($product->id)) ;
                                $productName=$product->name;
                                $trimstring = substr(strip_tags($product->full_detail), 0, 100). '...&nbsp; &nbsp;<a href="'.$href.'" title="'.$productName.'">Read more</a>';
                            } else {
                                $trimstring = strip_tags($product->full_detail);
                            }
                            ?>
                            {!! $trimstring !!}
                        </p>
                    </div>
                </div><!-- /.box-content -->
                <div class="box-price">
                    <div class="price">

                        @if(Auth::check() && Auth::guard('web')->check() && Auth::user()->type != 'retailer')
                            <span class="sale">£{{ $product->discountedPrice }}</span>
                        @else
                            @if($product->discount_type>0)
                                <span class="regular">£{{ number_format($product->price,2) }}</span>
                            @endif
                            <span class="sale">£{{ $product->discountedPrice }}</span>
                        @endif

                    </div>
                    <div class="btn-add-cart">
                        <a href="javascript:void(0)" title="" onclick="addToCart({{$product->id}})">
                            <img src="{{asset('front_assets/images/icons/add-cart.png')}}" alt="">Add to Cart
                        </a>
                    </div>
                    <div class="compare-wishlist">
                        <a href="javascript:void(0)" class="wishlist" title="" onclick="addRemoveWishlist({{$product->id}})">
                            {{$product->isFavorite?'Yes':'No'}}
                            <img src="{{asset('front_assets/images/icons/wishlist.png')}}" alt="">Wishlist
                        </a>
                    </div>
                </div><!-- /.box-price -->
            </div><!-- /.imagebox -->
        </div>
        @empty
        <div>
            No Records Found
        </div>
        @endforelse
        <div class="blog-pagination style1 list_pagination">
            <!-- <span>
                Showing 1–15 of 20 results
            </span> -->
            <div id="pagination">
                {{$products->render()}}
            </div>
            <div class="clearfix"></div>
        </div><!-- /.blog-pagination -->
        <div style="height: 9px;"></div>
    </div>
</div>
