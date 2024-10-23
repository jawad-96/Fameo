@extends('layouts.app')

@section('content')

<div class="popup-newsletter">
    <div class="container">
        <div class="row">
            <div class="col-sm-2">

            </div>
            <div class="col-sm-8">
                <div class="popup">
                    <span></span>
                    <div class="popup-text">
                        <h2>Join our newsletter and <br />get discount!</h2>
                        <p class="subscribe">Subscribe to the newsletter to receive updates about new products.</p>
                        <div class="form-popup">
                            <form action="#" class="subscribe-form" method="get" accept-charset="utf-8">
                                <div class="subscribe-content">
                                    <input type="text" name="email" class="subscribe-email" placeholder="Your E-Mail">
                                    <button type="submit"><img src="images/icons/right-2.png" alt=""></button>
                                </div>
                            </form><!-- /.subscribe-form -->
                            <div class="checkbox">
                                <input type="checkbox" id="popup-not-show" name="category">
                                <label for="popup-not-show">Don't show this popup again</label>
                            </div>
                        </div><!-- /.form-popup -->
                    </div><!-- /.popup-text -->
                    <div class="popup-image">
                        <img src="images/banner_boxes/popup.png" alt="">
                    </div><!-- /.popup-text -->
                </div><!-- /.popup -->
            </div><!-- /.col-sm-8 -->
            <div class="col-sm-2">

            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.popup-newsletter -->

@endsection

@section('scripts')
<script type="text/javascript">

    $(document).ready(function() {return false;

        var url = "{{ url('get-my-orders') }}";
        getMyOrders(url, 500);
    });

    function addToCart(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ url('cart-add') }}',
            method: "POST",
            data: {id: id},
            success: function (response) {
                $('#cartTotal').text(response.cartTotal);
                $('#cartPrice').text('£'+response.cartPrice);
                toastr.success("Item successfully added to your cart");
            }
        });
    }

    function removeWishlist(product_id){

        var url = "{{ url('get-wishlist') }}";

        getWishlist(url, 500, product_id);
    }

</script>
@endsection
