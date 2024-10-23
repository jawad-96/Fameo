@extends('admin.layouts.app')

@section('content')

    <link rel="stylesheet" type="text/css" href="{{url('front_assets/stylesheets/style.css')}}">

    <section id="main-content" >
        <section class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                        <li><a href="{{ url('admin/courier-assignment') }}">Courier Assignment</a></li>
                        <li class="active">Update</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

            <section class="flat-shop-cart">

                <div class="container">
                    {{--                <form action="{{url('admin/courier-assignment/'.$courierAssignment->id.'/store')}}" id="myform" method="POST" enctype="multipart/form-data">--}}


                        @include('admin.courier-assignment.cartDetails')



                    {{--




                    {{--                <div id="cart_details">--}}

                    {{--                </div>--}}

                </div><!-- /.container -->

            </section>

        </section>
    </section>


@endsection


@section('scripts')




    <script type="text/javascript">

        $(document).ready(function() {
            $('.top-nav').removeClass("clearfix");

            getCartDetails();
        });

        function getCartDetails(){
            $.ajax({
                {{--url: '{{ url('admin/courier-assignment/cartDetails/'.$courierAssignment->id) }}',--}}
                url: '{{ url('cart-details1/'.$courierAssignment->id) }}',
                method: "get",
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
