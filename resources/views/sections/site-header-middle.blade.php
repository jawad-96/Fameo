<div class="header-middle">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div id="logo" class="logo">
                    <a href="{{ url('/') }}" title="{{ settingValue('site_title') }}">
                        <img src="{{asset('uploads/settings/site_logo.jpg')}}" alt="{{ settingValue('site_title') }}">
                    </a>
                </div><!-- /#logo -->
            </div><!-- /.col-md-3 -->
            <div class="col-md-6">
                <div class="top-search">
                    <form class="form-search">
                        <div class="cat-wrap">
                            <select name="category_id" id="searchCategory">
                                {{--@if(Request::get('category_id')>0)
                                    <option hidden value="{{ Request::get('category_id') }}">{{ getCategoryNameById(Request::get('category_id')) }}</option>
                                @else
                                    <option hidden value="">All Category</option>
                                @endif--}}
                                <option>All Category</option>
                            </select>
                            <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                            <div class="all-categories">
                                @foreach(getCategories() as $category)
                                <div class="cat-list-search">


                                    @if (isset($products_check))
                                        <div class="title categoryType" id="{{$category->id.'-'.$category->name}}">{{ $category->name }}</div>
                                    @else

                                        <div>
                                            <a href="{{url('products?category='.$category->id.'-'.string_replace($category->name).'&view-type=grid')}}" title="">{{ $category->name }}</a>
                                        </div>
                                    @endif

                                    @if($category->subcategories->count()>0)
                                    <ul>
                                        @foreach($category->subcategories as $subcategory)
                                            @if (isset($products_check))
                                                <li class="categoryType" id="{{$subcategory->id.'-'.$subcategory->name}}">{{ $subcategory->name }}</li>
                                            @else
                                                <li>
                                                    <a href="{{url('products?category='.$subcategory->id.'-'.string_replace($subcategory->name).'&view-type=grid')}}" title="">{{ $subcategory->name }}</a>
                                                </li>
                                            @endif

                                        @endforeach
                                    </ul>
                                    @endif
                                </div><!-- /.cat-list-search -->
                                @endforeach
                            </div><!-- /.all-categories -->
                            <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                        </div><!-- /.cat-wrap -->
                    </form>
                    <form action="{{ url('products') }}" method="get" class="form-search" accept-charset="utf-8">
                        <input type="hidden" name="category" value="all" class="records">
                        <input type="hidden" name="records" value="10" class="records">
                        <input type="hidden" name="order" value="desc">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="filter" value="true">
                        <input type="hidden" name="view-type" value="grid">
                        <div class="box-search">
                            <input type="text" class="setCatName" name="search" value="{{ @Request::get('search') }}" placeholder="Search what you looking for ?" autocomplete="off">
                            <span class="btn-search">
                                <button type="submit" class="waves-effect"><img src="{{asset('front_assets/images/icons/search.png')}}" alt=""></button>
                            </span>
                        </div><!-- /.box-search -->
                    </form><!-- /.form-search -->
                </div><!-- /.top-search -->
            </div><!-- /.col-md-6 -->
            <div class="col-md-3">
                <div class="box-cart">
                    <div class="inner-box">
                        <ul class="menu-compare-wishlist">
                            <!-- <li class="compare">
                                <a href="compare.html" title="">
                                    <img src="{{asset('front_assets/images/icons/compare.png')}}" alt="">
                                </a>
                            </li> -->
                            <li class="wishlist">
                                <a href="{{url('my-wishlist')}}" title="">
                                    <img src="{{asset('front_assets/images/icons/wishlist.png')}}" alt="">
                                </a>
                            </li>
                        </ul><!-- /.menu-compare-wishlist -->
                    </div><!-- /.inner-box -->
                    <div class="inner-box">
                        <a href="{{route('cart.checkout')}}" title="">
                            <div class="icon-cart">
                                <img src="{{asset('front_assets/images/icons/cart.png')}}" alt="">
                                <span id="cartTotal">
                                    {{(Auth::id())?Cart::session(Auth::id())->getTotalQuantity():Cart::getTotalQuantity()}}
                                </span>
                            </div>
                            <div class="price" id="cartPrice">
                                £{{(Auth::id())?number_format(Cart::session(Auth::id())->getSubTotal(), 2):number_format(Cart::getSubTotal(), 2)}}
                            </div>
                        </a>

                        <!-- <div class="dropdown-box">
                            <ul>
                                <li>
                                    <div class="img-product">
                                        <img src="{{asset('front_assets/images/product/other/img-cart-1.jpg')}}" alt="">
                                    </div>
                                    <div class="info-product">
                                        <div class="name">
                                            Samsung - Galaxy S6 4G LTE <br />with 32GB Memory Cell Phone
                                        </div>
                                        <div class="price">
                                            <span>1 x</span>
                                            <span>$250.00</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <span class="delete">x</span>
                                </li>
                                <li>
                                    <div class="img-product">
                                        <img src="{{asset('front_assets/images/product/other/img-cart-2.jpg')}}" alt="">
                                    </div>
                                    <div class="info-product">
                                        <div class="name">
                                            Sennheiser - Over-the-Ear Headphone System - Black
                                        </div>
                                        <div class="price">
                                            <span>1 x</span>
                                            <span>$250.00</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <span class="delete">x</span>
                                </li>
                            </ul>
                            <div class="total">
                                <span>Subtotal:</span>
                                <span class="price">$1,999.00</span>
                            </div>
                            <div class="btn-cart">
                                <a href="shop-cart.html" class="view-cart" title="">View Cart</a>
                                <a href="shop-checkout.html" class="check-out" title="">Checkout</a>
                            </div>
                        </div> -->
                    </div><!-- /.inner-box -->
                </div><!-- /.box-cart -->
            </div><!-- /.col-md-3 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.header-middle -->
