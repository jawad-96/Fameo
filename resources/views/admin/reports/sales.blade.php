@extends('admin.layouts.app')

@section('style')
<style>
    .dataTables_length{float: left;}
    .dt-buttons{float: right; margin: 14px 0 0 0px;}
     div.dataTables_processing{top:55%;}
    .mini-stat{background: #f7f7f7;}
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
                    <li class="active">Sales Report</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Sales Report
                        
                        <span class="pull-right">
                            <div id="reportrange" class="pull-right report-range">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </span>
                        
                        <!-- {!! getStoreDropdownHtml() !!} -->
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mini-stat clearfix bg-aqua" data-toggle="tooltip" title="Total Revenue">
                                    <div class="mini-stat-info">
                                        <span id="revenue">0</span>
                                        REVENUE
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-2">
                                <div class="mini-stat clearfix">
                                    <div class="mini-stat-info" data-toggle="tooltip" title="" data-original-title="Number of sales in this time period">
                                        <span id="cost_of_goods">0</span>
                                        COST OF GOODS
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Number of unique registered customers served in a time period">
                                        <span id="gross_profit">0</span>
                                        GROSS PROFIT
                                    </div>
                                </div>
                            </div>                           
                            <div class="col-md-2">
                                <div class="mini-stat clearfix">
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Total revenue less the cost of goods sold">
                                        <span id="margin">0</span>
                                        MARGIN %
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-md-2">
                                <div class="mini-stat clearfix">
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Total amount discounted for this time period">
                                        <span id="tax">0.00</span>
                                        TAX
                                    </div>
                                </div>
                            </div>                              
                        </div>
                        
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Revenue</th>
                                <th>Cost of Goods</th>
                                <th>Gross Profit</th>
                                <th>Margin %</th>
                                <th>Tax</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="6" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-right">Total</th>
                                <th></th>
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
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" ></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" ></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" ></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" ></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" ></script>

<script type="text/javascript">
    var table;
$("document").ready(function () {
    
    var start = moment().subtract(6, 'days');
    var end = moment();
    
    var datatable_url = "{{url('admin/reports/sale')}}";
    $('#datatable').DataTable({
            dom: 'lBfrtip',
            buttons: [{
                text: '<span data-toggle="tooltip" title="Export CSV"><i class="fa fa-lg fa-file-text-o"></i> CSV</span>',
                extend: 'csv',
                className: 'btn btn-sm btn-round btn-success',
                title: 'Sale Report',
                extension: '.csv',
                footer: true
            },{
                text: '<span data-toggle="tooltip" title="Print"><i class="fa fa-lg fa-print"></i> Print</span>',
                extend: 'print',
                title: 'Sale Report',
                className: 'btn btn-sm btn-round btn-info',
                footer: true
            },{
                extend: 'pdf',
                text: '<span data-toggle="tooltip" title="Export PDF"><i class="fa fa-lg fa-file-pdf-o"></i> PDF</span>',
                className: 'btn btn-sm btn-round btn-danger',
                title: 'Sale Report',
                extension: '.pdf',
                footer: true
            }],
            processing: true,
            serverSide: true,
            ajax: {
                url: datatable_url,
                data : function(d){
                    d.store_id = $("#store_reports option:selected").val();                       
                    d.from_date= start.format('YYYY/MM/DD');
                    d.to_date= end.format('YYYY/MM/DD');
                    }
            }, 
            columns: [
                {data: 'date', name: 'date', className: 'text-center'},
                {data: 'revenue', name: 'revenue', className: 'text-center'},             
                {data: 'cost_of_goods', name: 'cost_of_goods', className: 'text-center'},                 
                {data: 'gross_profit', name: 'gross_profit', className: 'text-center'},                 
                {data: 'margin', name: 'margin', className: 'text-center'}, 
                {data: 'order_tax', name: 'order_tax', className: 'text-center'},                 
            ],
            "order": [],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                };

                revenueTotal = api.column( 1, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                cgTotal = api.column( 2, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                gpTotal = api.column( 3, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                marginTotal = api.column( 4, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                taxTotal = api.column( 5, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html( '<b>'+revenueTotal +'</b>' );
                $( api.column( 2 ).footer() ).html( '<b>'+cgTotal +'</b>' );
                $( api.column( 3 ).footer() ).html( '<b>'+gpTotal +'</b>' );
                $( api.column( 4 ).footer() ).html( '<b>'+marginTotal +' %</b>' );
                $( api.column( 5 ).footer() ).html( '<b>'+taxTotal +'</b>' );
                
                $("#revenue").text(revenueTotal);
                $("#cost_of_goods").text(cgTotal);
                $("#gross_profit").text(gpTotal);
                $("#margin").text(marginTotal+' %');
                $("#tax").text(taxTotal);
                
                $(".mini-stat").LoadingOverlay("hide");
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
