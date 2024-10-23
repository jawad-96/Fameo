@extends('company.layouts.app')

@section('css')
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
                    <li><a href="{{ url('company/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Staff Report</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Staff Report
                        
                        <span class="pull-right">
                            <div id="reportrange" class="pull-right report-range">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </span>
                        
                        {!! getStoreDropdownHtml() !!}
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Sales</th>
                                <th>Total Amount</th>                                
                                <th>Total Discount</th>
                                <th>Total Tax</th>
                            </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total</th>                                
                                <th></th>
                                <th></th>
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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" ></script>

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
            title: 'Export CSV',
            extension: '.csv'
        },{
            text: '<span data-toggle="tooltip" title="Print"><i class="fa fa-lg fa-print"></i> Print</span>',
            extend: 'print',
            className: 'btn btn-sm btn-round btn-info',
        }],
        oLanguage: { sProcessing: '<img src="'+ base_url +'/images/bx_loader.gif">',sEmptyTable: 'No matching records found' },
        processing: true,
        serverSide: true,
        ordering: true,
        responsive: true,
        ajax: {
                  url: "{{url('company/reports/get-staff-report')}}",
                  data : function(d){
                          d.store_id = $("#store_reports option:selected").val(); 
                          d.from_date= start.format('YYYY/MM/DD');
                          d.to_date= end.format('YYYY/MM/DD');
                      }
              },
        columns: [
              {data: 'name', name: 'name'},            
              {data: 'email', name: 'email'},
              {data: 'total_sales', name: 'total_sales', className: 'text-center'},
              {data: 'total_sale_amount', name: 'total_sale_amount', className: 'text-center'},
              {data: 'total_discount', name: 'total_discount', className: 'text-center'},                
              {data: 'total_tax', name: 'total_tax', className: 'text-center'},                
          ],
        order: [],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };
  
            salesTotal = api.column( 2, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            saleAmountTotal = api.column( 3, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            discountTotal = api.column( 4, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            taxTotal = api.column( 5, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
 
            // Update footer
            $( api.column( 2 ).footer() ).html( '<b>'+salesTotal +'</b>' );
            $( api.column( 3 ).footer() ).html( '<b>'+saleAmountTotal +'</b>' );
            $( api.column( 4 ).footer() ).html( '<b>'+discountTotal +'</b>' );
            $( api.column( 5 ).footer() ).html( '<b>'+discountTotal +'</b>' );
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
                          
