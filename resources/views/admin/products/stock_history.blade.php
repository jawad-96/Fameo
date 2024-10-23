@extends('admin.layouts.app')

@section('style')
    <style>
        table tbody tr{cursor: pointer;}
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
                    <li><a href="{{ url('admin/products') }}"> Products</a></li>
                    <li class="active">Stock History</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        {{ $product->name }}                  
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Store Name</th>
                                <th>Type</th>
                                <th>Task</th>
                                <th>Stock Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Store Name</th>
                                <th>Type</th>
                                <th>Task</th>
                                <th>Stock Quantity</th>
                            </tr>
                          </tfoot>
                        </table>
                    </div>
                </section>
            </div>
        </div>   
            
    </section>
</section>
 
   
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="stock_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Stock Adjustment</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Date:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="stock_date"></p>
                        </div>
                    </div>                           
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Store Name:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="stock_store"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Product Name:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="product_name"></p>
                        </div>
                    </div>                           
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Type:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="stock_type"></p>
                        </div>
                    </div>                           
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Task:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="stock_task"></p>
                        </div>
                    </div>                           
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Stock Quantity:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="stock_quantity"></p>
                        </div>
                    </div>              
                    <div class="form-group">
                        <label class="col-lg-4 col-sm-4 control-label">Note:</label>
                        <div class="col-lg-8">
                            <p class="form-control-static" id="stock_note"></p>
                        </div>
                    </div>                                                      

                </form>

            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript">
$("document").ready(function () {
    var datatable_url = "{{url('admin/get-product-stocks')}}"+'/'+{{ $product->id }};
    var datatable_columns = [
        {data: 'created_at', width: '20%'},
        {data: 'store_name'},
        {data: 'stock_type', width: '10%'},
        {data: 'origin', width: '15%'},
        {data: 'quantity', width: '15%'},
//            {data: 'action', name: 'action', width: '10%', orderable: false, searchable: false}
        ];
            
    var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: datatable_url,
                data : function(d){
                    if($(".filter_by_store").val() != ''){
                        d.columns[1]['search']['value'] = $(".filter_by_store option:selected").text();
                    }                    
                    }
            }, 
            columns: datatable_columns,
            "order": []
        });   
        
    $('#datatable tbody').on('click', 'tr', function () 
    {
        var tr = $(this);
        var row = table.row( tr );
        var stock = row.data();
        
        console.log(stock);
        
        $("#stock_date").text(stock.created_at);
        $("#stock_store").text(stock.store_name);
        $("#product_name").text(stock.product_name);
        $("#stock_type").text(stock.stock_type);
        $("#stock_task").text(stock.origin);
        $("#stock_quantity").text(stock.quantity);
        $("#stock_note").text(stock.note);

        $('#stock_model').modal({show:true});        
        
    });
    
        $("#datatable_length").append('{!! Form::select("type", getStoresFilterDropdown(), null, ["class" => "form-control input-sm filter_by_store","style"=>"margin-left: 20px;"]) !!}');
        
        var reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );
        
        $(document).on('change', '.filter_by_store', function (e) {
            reload_datatable.fnDraw();
        });
        
           
      });            
</script>
@endsection                            
