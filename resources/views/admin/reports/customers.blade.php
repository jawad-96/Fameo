@extends('admin.layouts.app')

@section('style')
<style>
    .dataTables_length{float: left;}
    .dt-buttons{float: right; margin: 14px 0 0 0px;}
    div.dataTables_processing{top:55%;}
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
                    <li class="active">Customers Report</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Customers Report
                        
                        <span class="pull-right">
                            <div id="reportrange" class="pull-right report-range">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </span>
                        
                        <!-- {!! getStoreDropdownHtml() !!} -->
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <!-- <th>Store</th> -->
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Total Sale</th>
                                <th>Total Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th class="text-right">Total</th>
                                <th></th>
                                <th></th>
                            </tr>
                          </tfoot>
                        </table>
                    </div>
                </section>
            </div>
        </div>   
            
    </section>
</section>
      
@endsection


@section('scripts')
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" ></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" ></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" ></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" ></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" ></script>

<script type="text/javascript">
    var upload_url = '{{ asset("uploads") }}';
    
$(document).ready(function () {
    
    var start = moment().subtract(6, 'days');
    var end = moment();
    
    var table = $('#datatable').DataTable({
        dom: 'lBfrtip',
        buttons: [{
            text: '<span data-toggle="tooltip" title="Export CSV"><i class="fa fa-lg fa-file-text-o"></i> CSV</span>',
            extend: 'csv',
            className: 'btn btn-sm btn-round btn-success',
            title: 'Customer Report',
            extension: '.csv',
            footer: true
        },{
            text: '<span data-toggle="tooltip" title="Print"><i class="fa fa-lg fa-print"></i> Print</span>',
            extend: 'print',
            title: 'Customer Report',
            className: 'btn btn-sm btn-round btn-info',
            footer: true
        },{
            extend: 'pdf',
            text: '<span data-toggle="tooltip" title="Export PDF"><i class="fa fa-lg fa-file-pdf-o"></i> PDF</span>',
            className: 'btn btn-sm btn-round btn-danger',
            title: 'Customer Report',
            extension: '.pdf',
            footer: true
        }],
        processing: true,
        serverSide: true,
        ordering: true,
        responsive: true,
        ajax: {
                  url: "{{url('admin/reports/customer')}}",
                  data : function(d){
                          d.store_id = $("#store_reports option:selected").val(); 
                          d.from_date= start.format('YYYY/MM/DD');
                          d.to_date= end.format('YYYY/MM/DD');
                      }
              },
        columns: [
              {data: 'name', name: 'name'},       
              //{data: 'store_name', name: 'store_name'},       
              {data: 'mobile', name: 'mobile'},       
              {data: 'email', name: 'email'},
              {data: 'total_sales', name: 'total_sales', className: 'text-center', width:'10%'},
              {data: 'total_sale_amount', name: 'total_sale_amount', className: 'text-center'},
          ],
        order: [],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };
  
            salesTotal = api.column(3, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            saleAmountTotal = api.column(4, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            // Update footer
            $( api.column(3).footer() ).html( '<b>'+salesTotal +'</b>' );
            $( api.column(4).footer() ).html( '<b>'+ saleAmountTotal.toFixed(2) +'</b>' );
        }
      });        
        
    var reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );
                
    $(document).on('change', '#store_reports', function(){
        cb(start, end);
    });
    
    function cb(from_date, end_date) {
        $(".mini-stat").LoadingOverlay("show");
        start = from_date;
        end = end_date;        
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));        
        reload_datatable.fnDraw();
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
    }, cb);

    cb(start, end);
    
});
       
</script>
@endsection                            
                          
