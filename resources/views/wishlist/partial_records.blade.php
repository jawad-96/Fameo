<div class="wishlist-content">
    <table class="table-wishlist">
        <thead>
            <tr>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Stock Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($wishlist_records as $wishlist)
            @if(@$wishlist->product)
            @php
                $product = $wishlist->product;
            @endphp
            <tr>
                <td>
                    <div class="delete">
                        <!-- <a href="javascript:void(0)" title="" onclick="removeWishlist({{$wishlist->product->id}})"><img src="{{asset('front_assets/images/icons/delete.png')}}" alt=""></a> -->
                        <button class="btn btn-danger btn-sm remove-from-cart" onclick="removeWishlist({{$product->id}})"><i class="fa fa-trash-o"></i></button>
                    </div>
                    <div class="product">
                        <a href="{{ url('products/'.Hashids::encode($product->id)) }}" class="image">
                            <img src="{{ $product->default_image_url }}" alt="">
                        </a>
                        <div class="name">
                            {{$product->name}} <br />{{$product->code}}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="price">
                        <span class="sale">£{{ $product->discountedPrice }}</span>
                        @if($product->discount_type>0)
                            <span class="regular">£{{ number_format($product->price,2) }}</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="status-product">
                        @if(@$product->store_products[0]->quantity>0)
                            <span style="background-color: green;">In stock</span>
                        @else
                            <span>Out of stock</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="add-cart">
                        <a href="javascript:void(0)" title="" onclick="addToCart({{$product->id}})">
                            <img src="{{asset('front_assets/images/icons/add-cart.png')}}" alt="">Add to Cart
                        </a>
                    </div>
                </td>
            </tr>

            @endif
        @empty
            <tr><td colspan="4">No Records Found</td></tr>
        @endforelse
        </tbody>
    </table><!-- /.table-wishlist -->
</div><!-- /.wishlist-content -->
