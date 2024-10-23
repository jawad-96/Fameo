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
                @if($count > 0)
                    <form class="form-horizontal" role="form" id="form" method="POST" action="{{url('admin/courier-assignment/'.Hashids::encode($courierAssignment->id).'/store')}}">
                        {{ csrf_field() }}
                        @include('admin.courier-assignment.cartDetails')

                        <div class="col-md-12" style="margin-top: 50px;  ">

                            <div class="col-md-3 pull-right">


                                <button type="submit" id="save_id"  class=" btn-success" title="">Save</button>
                                <a href="{{url('admin/cancel-request/'.$courierAssignment->id.'/admin')}}" class="btn btn-warning" title="">Cancel Order</a>

                            </div>


                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success " data-toggle="modal"
                                        data-target="#end" data-required_qty="" >
                                    <span>Approve</span>
                                </button>
                            </div>
                            <div class="col-md-3">






                            </div>

                        </div>

                    </form>
                @else
                    No details found
                @endif

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





        window.addEventListener( "pageshow", function ( event ) {
            var historyTraversal = event.persisted ||
                ( typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2 );
            if ( historyTraversal ) {
                // Handle page restore.
                window.location.reload();
            }




        });


        $('#save_id').on('click',function () {
           //
           //  var temp=0;
           //  var temp1=0;
           // $('.group_checkbox').each(function () {
           //
           //     if($(this).is(":checked")){
           //        temp++;
           //     }
           //     else if($(this).is(":not(:checked)")){
           //          temp1++
           //     }
           // })
           //
           //  if(temp > 0 || temp1 == 0)
           //  {
           //      $('#save_id').attr('disabled',true);
           //  }
           //  else
           //  {
           //      $('#save_id').attr('disabled',false);
           //  }


        })





        $("form").submit(function () {



            var this_master = $(this);

            var courier_id=$('.courier').val();

            var checked = $("#form input:checked").length > 0;

            var checkbox = $(".group_checkbox").length > 0;

            console.log('checked',$("#form input:checked").length);

            if (checkbox && !checked){
                 alert("Please check at least one checkbox");
                //alert(checked);
                return false;
            }
            if(courier_id > 0 )
            {


            this_master.find('input[type="checkbox"]').hide();

            this_master.find('input[type="checkbox"]').each( function () {
                var checkbox_this = $(this);


                if(checkbox_this.is(":checked") == true) {
                    checkbox_this.attr('value','1');
                } else {
                    checkbox_this.prop('checked',true);
                    //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
                    checkbox_this.attr('value','0');
                }
            });

            }
        });

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



        $(document).onclick(function () {


            var cou= $('.courier').val()

            if(cou == "")
            {

            }

        })
    </script>




@endsection
