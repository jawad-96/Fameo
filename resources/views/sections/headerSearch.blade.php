<div class="col-md-9 col-10">
    <div class="top-search style1">
        <form action="{{ url('products') }}" method="get" class="form-search" accept-charset="utf-8">
            <input type="hidden" name="page" value="1">
            <div class="cat-wrap cat-wrap-v1">
                <select name="category_id" id="searchCategory">                
                    <option hidden value="">All Category</option>                    
                </select>
                <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                <div class="all-categories">
                    @foreach(getCategories() as $category)
                    <div class="cat-list-search">
                        <div class="title">{{ $category->name }}</div>
                        @if($category->subcategories->count()>0)
                        <ul>
                            @foreach($category->subcategories as $subcategory)
                            <li data-id="{{ $subcategory->id }}">{{ $subcategory->name }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div><!-- /.cat-list-search -->
                    @endforeach
                </div><!-- /.all-categories -->
            </div><!-- /.cat-wrap -->
            <div class="box-search">
                <input type="text" name="search" value="{{ @Request::get('search') }}" placeholder="Search what you looking for ?" autocomplete="off">
                <span class="btn-search">
                    <button type="submit" class="waves-effect"><img src="{{asset('front_assets/images/icons/search-2.png')}}" alt=""></button>
                </span>
            </div><!-- /.box-search -->
        </form><!-- /.form-search -->
    </div><!-- /.top-search -->
    <span class="show-search">
        <button></button>
    </span>
    <div class="box-cart style1">
        <div class="inner-box">
            <ul class="menu-compare-wishlist">
                <li class="compare">
                    <a href="#" title="">
                        <img src="{{asset('front_assets/images/icons/compare-2.png')}}" alt="">
                    </a>
                </li>
                <li class="wishlist">
                    <a href="#" title="">
                        <img src="{{asset('front_assets/images/icons/wishlist-2.png')}}" alt="">
                    </a>
                </li>
            </ul><!-- /.menu-compare-wishlist -->
        </div><!-- /.inner-box -->
        <div class="inner-box">
            <a href="{{url('cart')}}" title="">
                <div class="icon-cart">
                    <img src="{{asset('front_assets/images/icons/add-cart.png')}}" alt="">
                    <span>
                        {{(Auth::id())?Cart::session(Auth::id())->getTotalQuantity():Cart::getTotalQuantity()}}
                    </span>
                </div>
                <div class="price">
                    ${{(Auth::id())?Cart::session(Auth::id())->getSubTotal():Cart::getSubTotal()}}
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
                                Samsung - Galaxy S6 4G LTE with 32GB Memory Cell Phone
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
                    <a href="#" class="view-cart" title="">View Cart</a>
                    <a href="#" class="check-out" title="">Checkout</a>
                </div>
            </div> -->
        </div><!-- /.inner-box -->
    </div><!-- /.box-cart -->
</div><!-- /.col-md-9 -->