@extends('admin.layouts.app')

@section('style')
<style>
    td span.details-control {
        background: url(../images/details_open.png) no-repeat center center;
        cursor: pointer;
        width: 18px;
        padding: 12px;
    }
    tr.shown td span.details-control {
        background: url(../images/details_close.png) no-repeat center center;
    }
</style>
@endsection

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Orders</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Orders
                         <span class="pull-right">
                            <div id="reportrange" class="pull-right report-range">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </span>
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th> Order Id </th>
                                    <th> Email </th>
                                    <th> Amount </th>
                                    {{-- <th> Label Image </th> --}}
                             
                                    @if(auth()->user()->can('change order status'))
                                    <th> Status </th>
                                    @endif
                                    @if(auth()->user()->can('change order status'))
                                        <th> Courier </th>
                                    @endif
                                    @if(auth()->user()->can('view order invoice'))
                                    <th> Action </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="6" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th> Order Id </th>
                                    <th> Email </th>
                                    <th> Amount </th>
                                    {{-- <th> Label Image </th> --}}
                          
                                    @if(auth()->user()->can('change order status'))
                                    <th> Status </th>
                                    @endif
                                    @if(auth()->user()->can('change order status'))
                                        <th> Courier </th>
                                    @endif
                                    @if(auth()->user()->can('view order invoice'))
                                    <th> Action </th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="transaction_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Transaction Details</h4>
            </div>
            <div class="modal-body">

                <form role="form" class="form-horizontal" data-toggle="validator" data-disable = 'false' id="ss">
                    <div class="row">

                        <div id="variant_fields"></div>

                        <h5 class="col-lg-12"><b>Payer Infor:</b></h5>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Payer Name:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="payer_name"></span>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Payer Email:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="payer_email"></span>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Shipping Address:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="shipping_address"></span>
                            </div>
                        </div>
                        <h5 class="col-lg-12"><b>Payment Info:</b></h5>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Transaction No:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="trans_number"></span>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Total Deposit:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="amount"></span>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Payment via:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="pay_via"></span>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <b>Status:</b>
                            </div>
                            <div class="col-lg-offset-1 col-lg-5">
                                <span class="payment_status"></span>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="note_add_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Transaction Note</h4>
            </div>
            <div class="modal-body">

                <form role="form" class="form-horizontal" data-toggle="validator" data-disable = 'false' id="note_add_form">
                    <div class="row">
                        <h5 class="col-lg-12"><b>Note:</b></h5>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="hidden" id="note_order_id" value='0' />
                                <textarea class="form-control" rows="10" id="note_add_form_textarea"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-10 col-lg-10">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="note_view_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Transaction Note</h4>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <h6 class="col-lg-12 noteViewModelNote"></h6>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    var table;
    @if(request()->from_date)
        var start = moment('{{ request()->from_date }}');
    @else
        var start = moment().subtract(6, 'days');
    @endif
    @if(request()->to_date)
        var end = moment('{{ request()->to_date }}');
    @else
        var end = moment();
    @endif
    
    var upload_url = '{{ asset("uploads") }}';
    var  $reload_datatable={};
     var url = window.location.href;
    function cb(start, end) {
        $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        var current_url     = window.location.href;
        var from_date_param  = getParams('from_date');
        var updated_url = current_url.replace("from_date="+from_date_param, "from_date="+start.format('YYYY-MM-DD'));
        window.history.pushState("", "", updated_url);

        var current_url     = window.location.href;
        var to_date_param  = getParams('to_date');
        var updated_url = current_url.replace("to_date="+to_date_param, "to_date="+end.format('YYYY-MM-DD'));
        window.history.pushState("", "", updated_url);
    }
    
       cb(start, end);
    $("document").ready(function () {

        var current_url     = window.location.href;
        if((current_url.indexOf('from_date') == -1)){
            var default_url = window.location.href+'?user_id=0&from_date='+start.format('YYYY-MM-DD')+'&to_date='+end.format('YYYY-MM-DD')+'&start=0&length=10';
            window.history.pushState("", "", default_url);
        }
        
        @if(request()->user_id)
        var current_url     = window.location.href;
        var user_id_param  = getParams('user_id');
        var updated_url = current_url.replace("user_id="+user_id_param, "user_id={{request()->user_id}}");
        window.history.pushState("", "", updated_url);
        @endif

    loadDatatable(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
       
        var reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );

        $(document).on('click', '.addViewOrderNote', function ()
        {
            var note = $(this).data('note');
            var id = $(this).data('id');

            if (note != '') {
                $(".noteViewModelNote").text(note);
                $('#note_view_model').modal('show');
            } else {
                $("#note_order_id").val(id);
                $('#note_add_form_textarea').val('');
                $('#note_add_model').modal('show');
            }
        });
        
        $(document).on('submit', '#note_add_form', function (e)
        {
            e.preventDefault();
            var id = $('#note_order_id').val();
            var note = $('#note_add_form_textarea').val();

            if (id > 0 && note != '') {
                var url = "{{url('admin/save-order-note')}}";
                $.ajax({
                    url:url,
                    type:"post",
                    data: {id, note},
                    success: function (response) {
                        $("#note_order_id").val(0);
                        $('#note_add_form_textarea').val('');
                        $('#note_add_model').modal('hide');
                        $reload_datatable.fnDraw();
                        success_message("Note successfully saved");
                    }
                });
            } else {
                error_message('Note is required');
            }
            return false;
        });

        $('#datatable tbody').on('click', 'td span.details-control', function ()
        {
            var tr = $(this).closest('tr');console.log(tr);
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        } );
        

        $(document).on('click', '.refresh_products', function (e) {
            reload_datatable.fnDraw();
        });

        /*child*/


         $reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );
        /*change shipping status*/
        $(document.body).on('change',".change_status",function (e) {

            var status= $(this).val();
            var id = $(this).attr("data-id");
            var cart_id = $(this).attr("data-cart-id");
            var url= "{{url('admin/orders/change-status')}}"+'/'+id+'/'+cart_id;
            var data_object = {status:status, id:id};
            // change_status(url, data_object, $reload_datatable);
            $.ajax({
                url:url,
                type:"put",
                data:data_object,
                success:function (res) {
                    if (res == 'true') {
                        $reload_datatable.fnDraw();
                        success_message("Delivery status updated successfully");
                    }
                },
                error: function (request, status, error) {
                    error_message(request.responseText);
                }
            });//..... end of ajax() .....//
        });
        $("body").on("click",'.bt-download',function () {
            var url = $(this).attr('image-url');
            printImg(url);
        });

        $("body").on('change','.courier_send' ,function () {
            var status= $(this).val();
            var id = $(this).attr("data-id");
            var cart_id = $(this).attr("data-cart-id");
            var url= "{{url('admin/orders/change-courier')}}"+'/'+id+'/'+cart_id;
            var data_object = {status:status, id:id};
            // change_status(url, data_object, $reload_datatable);
            $.ajax({
                url:url,
                type:"put",
                data:data_object,
                success:function (res) {
                    if (res == 'true') {
                        $reload_datatable.fnDraw();
                        success_message("Delivery status updated successfully");
                    }
                },
                error: function (request, status, error) {
                    error_message(request.responseText);
                }
            });//..... end of ajax() .....//
        })
        
             $("body").on('change','.status_update' ,function () {
            var status= $(this).val();
            var cart_id = $(this).attr("data-id");
            var transaction_id = $(this).attr('data-transaction-id');
            var url= "{{url('admin/orders/update-order-status')}}"+'/'+cart_id;
            var data_object = {status:status,transaction_id:transaction_id};
            // change_status(url, data_object, $reload_datatable);
            $.ajax({
                url:url,
                type:"put",
                data:data_object,
                success:function (res) {
                    if (res == 'true') {
                        $reload_datatable.fnDraw();
                        success_message("Delivery status updated successfully");
                    }
                },
                error: function (request, status, error) {
                    error_message(request.responseText);
                }
            });//..... end of ajax() .....//
        })
    });
    function printImg(url) {

        var win = window.open('');
        win.document.write('<img src="' + url + '" onload="window.print();window.close()" />');
        win.focus();
    }
    function format ( rowData ) {
        var div = $('<div/>').addClass( 'loading' ).text( 'Loading...' );

        var product_html = '<table class="table table-bordered table-striped">\
                            <thead><tr>\
                                <th>Name</th>\
                                <th>Quantity</th>\
                                <th>Courier Name</th>\
                                <th>Courier Charges/Item</th>\
                            </tr></thead><tbody>';
                                console.log('rowData '+rowData);
        $.ajax({
            url: "{{ url('admin/get-order-details') }}" + '/' + rowData.id,
            type:"get",
            success: function (response) {
                var products = response.products;
                if(products.length>0){
                    $.each(products,function(index, item) {
                        product_html += '<tr>\
                                    <td align="left" width="30%">'+ item.product_name +'</td>\
                                    <td align="left" width="5%">'+ item.product_quantity +'</td>\
                                    <td align="left" width="20%">'+ item.courier_name +'</td>\
                                    <td align="left" width="10%">'+ item.courier_charges +'</td>\
                                </tr>';
                    });
                }else{
                    product_html += '<tr><td colspan="7">Record not found</td></tr> ';
                }
            },
            complete: function (res) {
                product_html += '</tbody></table>';
                div.html( product_html ).removeClass( 'loading' );
            }
        });

        return div;
        
        // var products = rowData.purchased_items;

        

        // if(products.length>0){
        //     console.log(products);

        //     $.each(products,function(index, item) {

        //         id = 1;
        //         var image = '<img width="30" src="'+ upload_url +'/no-image.png" />';
        //         if(item.product.product_images.length>0){
        //             image = '<img width="30" src="' + upload_url +'/products/thumbs/'+ item.product.product_images[0].name +'" />'
        //         }
        //         var discount_type = 'No';
        //         if(item.product.discount_type == 1){
        //             discount_type = 'Percentage';
        //         }else{
        //             discount_type = 'Fixed';
        //         }

        //         product_html += '<tr>\
        //                             <td align="left" width="10%">'+ image +'</td>\
        //                             <td align="left" width="20%">'+ item.product.name +'</td>\
        //                             <td align="left" width="10%">'+ item.product.code +'</td>\
        //                             <td align="left" width="10%">'+ item.quantity +'</td>\
        //                             <td align="left" width="10%">'+ item.product.price +'</td>\
        //                         </tr>';

        //     });
        // }else{

        //     product_html += '<tr><td colspan="7">Record not found</td></tr> ';
        // }

        // product_html += '</tbody></table>';

        // div.html( product_html ).removeClass( 'loading' );

        // return div;
    }

    function load_model(id){

        /*$.ajax({
            url: '{{ url('get-transaction-details') }}',
            method: "post",
            data: {_token: '{{ csrf_token() }}', id: id},
            success: function (response) {
                console.log(response.data);
                if(response.status){
                    $('.payer_name').text(response.data.payer_name.full_name);
                    $('.payer_email').text(response.data.payer);
                    $('.shipping_address').text(response.data.shipping_address);
                    $('.trans_number').text(response.data.trans_id);
                    $('.amount').text(response.data.amount + ' ' + response.data.currency);
                    $('.pay_via').text('PayPal');
                    $('.status').text('Approved');
                    hide_loader();
                }
            }
        });*/

        // $('#transaction_model').modal('show');return false;

        var url = "{{url('get-transaction-details')}}";
        $.ajax({
            url:url,
            type:"post",
            data: {id: id},
            success: function (response) {
                console.log(response.data);
                if(response.status){
                    $('.payer_name').text(response.data.payer_name.full_name);
                    $('.payer_email').text(response.data.payer);
                    $('.shipping_address').text(response.data.shipping_address);
                    $('.trans_number').text(response.data.trans_id);
                    $('.amount').text(response.data.amount + ' ' + response.data.currency);
                    $('.pay_via').text('PayPal');
                    $('.payment_status').text('Approved');
                    $('#transaction_model').modal('show');
                }
            }
        });//..... end of ajax() .....//
    }
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    },cb);
    
     $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
    $('#reportrange').val('');
    $reload_datatable.fnDraw();
    });

  $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
   startdate = picker.startDate.format('YYYY-MM-DD');
   enddate = picker.endDate.format('YYYY-MM-DD');
 
   
    $('#datatable').DataTable().destroy();
   loadDatatable(startdate,enddate)
 
    });
    function loadDatatable(start_date='',end_date=''){

    var requestData = {from_date:start_date,to_date:end_date};
    //requestData["start"] = 40;
    //requestData["length"] = 10;

    table = $('#datatable').DataTable({
          processing: true,
          serverSide: true,
          ordering: true,
          responsive: true,
          ajax: {
                url:url,
                data: requestData
          },
          //,draw:2
          columns: [
                {data: 'orders_details', width: "2%", orderable:false, searchable: false},
                {data: 'orderId', width: "5%"},
                {data: 'email', width: "10%"},
                {data: 'amount', width: "5%"},
                // {data: 'barcode_image', width: "10%"},
             
                @if(auth()->user()->can('change order status'))
                {data: 'status', width: "10%", orderable: false, searchable: false},
                @endif
                  @if(auth()->user()->can('change order status'))
              {data: 'courier_service', width: "5%", orderable: false, searchable: false},
                  @endif
                @if(auth()->user()->can('view order invoice'))
                {data: 'action', width: "5%", orderable: false, searchable: false}
                @endif
            ],
          order: [],
          footerCallback: function (row, data, start, end, display) {
            var current_url     = window.location.href;
            var start_param  = getParams('start');
            var updated_url = current_url.replace("start="+start_param, "start="+start);
            window.history.pushState("", "", updated_url);
          }
        });
            }

        /*child*/

        var draw=1;
        $('#datatable').on( 'draw.dt', function () {
            if (draw==1) {
                @if(request()->start && request()->length)
                var pageNo = {{ (request()->start/request()->length)+2 }};
                setTimeout(function(){
                    $(".dataTables_paginate ul li:nth-child("+ pageNo +")").click();
                }, 1000);
                @endif
            }
            draw++;
        });



        function getParams( name, url)
        {
            if (!url) url = location.href;
            name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
            var regexS = "[\\?&]"+name+"=([^&#]*)";
            var regex = new RegExp( regexS );
            var results = regex.exec( url );
            return results == null ? null : results[1];
        }
</script>
@endsection
