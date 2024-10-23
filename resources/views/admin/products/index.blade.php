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
                    <li class="active">Products</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Products
                        <span class="pull-right" style="margin-top: -5px;">
                            @can('add products')
                            <a href="javascript:void(0)" class="btn btn-info btn-sm csv_products" data-toggle="tooltip" title="Upload CSV Products"><i class="fa fa-file" aria-hidden="true"></i></a>
                            @endcan
                            <a href="javascript:void(0)" class="btn btn-success btn-sm refresh_products" data-toggle="tooltip" title="Refresh Products"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                            @can('add products')
                            <a href="{{ url('admin/products/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New Product"><i class="fa fa-plus" aria-hidden="true"></i></a> @endcan                           
                         </span>
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Price</th>
                                <th>Discount %</th>
                                @if(auth()->user()->can('edit products') || auth()->user()->can('view product stocks') || auth()->user()->can('delete products'))
                                <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="10" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Price</th>
                                <th>Discount %</th>
                                @if(auth()->user()->can('edit products') || auth()->user()->can('view product stocks') || auth()->user()->can('delete products'))
                                <th>Action</th>
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
      
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="csv_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Upload CSV Products</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" action="{{ url('admin/upload-csv-products') }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">CSV File</label>
                        <div class="col-lg-10">
                            <input type="file" class="form-control" id="inputEmail4" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-10 col-lg-10">
                            <button type="submit" class="btn btn-success">Upload</button>
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
    var upload_url = '{{ asset("uploads") }}';
    
$(document).ready(function () {
    
    var table = $('#datatable').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      responsive: true,
      pageLength: 50,
      ajax: "{{url('admin/products')}}",
      columns: [
            {data: 'is_variants', orderable:false, searchable: false},
            {data: 'product_image', width: "10%", className: 'text-center'},
            {data: 'code', className: 'text-center'},
            {data: 'sku', className: 'text-center'},
            {data: 'name'},                 
            {data: 'supplier', className: 'text-center'},      
            {data: 'quantity.quantity', className: 'text-center'},           
            {data: 'cost', className: 'text-center'},                 
            {data: 'price', className: 'text-center'},    
            {data: 'discount', className: 'text-center'},    
            @if(auth()->user()->can('edit products') || auth()->user()->can('view product stocks') || auth()->user()->can('delete products'))                  
            {data: 'action', width: "10%", orderable: false, searchable: false}
            @endif
        ],
      order: []
    });
        
        $('#datatable tbody').on('click', 'td span.details-control', function () 
        {
            var tr = $(this).closest('tr');
            var row = table.row( tr );console.log(row);

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {                
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        } );
        
        var reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );
        
        $(document).on('click', '.refresh_products', function (e) { 
            reload_datatable.fnDraw(); 
        });   

        $(".csv_products").click(function(){
            $('#csv_model').modal('show');
        });     
      });
      
    function format ( rowData ) {
        var div = $('<div/>').addClass( 'loading' ).text( 'Loading...' );
        var products = rowData.products;

        var product_html = '<table class="table table-bordered table-striped">\
                            <thead><tr>\
                                <th>Image</th>\
                                <th>Code</th>\
                                <th>SKU</th>\
                                <th>Name</th>\
                                <th>Cost</th>\
                                <th>Price</th>';
        @if(auth()->user()->can('edit products') || auth()->user()->can('view product stocks') || auth()->user()->can('delete products'))                         
            product_html += '<th>Action</th>';
        @endif
        product_html += '</tr></thead><tbody>';
                                
        if(products.length>0){
            console.log(products);
    
            $.each(products,function(index, product){                
                
                var image = '<img width="30" src="'+ upload_url +'/no-image.png" />';
                if(product.product_images.length>0){
                    image = '<img width="30" src="' + upload_url +'/products/thumbs/'+ product.product_images[0].name +'" />'
                }
                
                var cost = '-';
                var price = '-';
                if(product.is_main_price==0){
                    cost = product.cost;
                    price = product.price;
                }                
                
                product_html += '<tr>\
                                    <td align="center" width="10%">'+ image +'</td>\
                                    <td align="center" width="10%">'+ product.code +'</td>\
                                    <td align="center">'+ product.sku +'</td>\
                                    <td>'+ product.name +'</td>\
                                    <td align="center" width="10%">'+ cost +'</td>\
                                    <td align="center" width="10%">'+ price +'</td>';
                @if(auth()->user()->can('edit products') || auth()->user()->can('view product stocks') || auth()->user()->can('delete products'))                      
                product_html += '<td>';
                @if(auth()->user()->can('edit products'))
                product_html += '<a href="products/edit/' + product.id +'" class="text-primary" data-toggle="tooltip" title="Edit Product"><i class="fa fa-lg fa-edit"></i></a>';
                @endif 
                @if(auth()->user()->can('view product stocks'))
                product_html += '<a href="product-stocks/' + product.id +'" class="text-success" data-toggle="tooltip" title="Stock History"><i class="fa fa-lg fa-history"></i></a>';
                @endif 
                @if(auth()->user()->can('delete stocks'))
                product_html += '<a href="javascript:void(0)" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Product" id="'+ product.id +'"><i class="fa fa-lg fa-trash"></i></a>';
                 @endif 
                 product_html += '</td>';
                 @endif                     
                 product_html += '</tr>';

            });
        }else{
                        
            product_html += '<tr><td colspan="7">Record not found</td></tr> ';
        }
        
        
                                        
        
        
        product_html += '</tbody></table>';
        
        div.html( product_html ).removeClass( 'loading' );
 
    return div;
}  
</script>
@endsection                            
