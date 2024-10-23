@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Payments</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Payments

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
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Note</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Note</th>
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


<script type="text/javascript">

    

    var token = $('meta[name="csrf-token"]').attr('content');
    var id = '{{ $id }}';
    $("document").ready(function () {

    var start = moment().subtract(6, 'days');
    var end = moment();
    var datatable_url = "{{url('admin/wholesaler/payments')}}"+'/'+id;
    var datatable_columns = [
        {data: 'date'},
        {data: 'amount'},
        {data: 'note'},
        ];
        
        //create_datatables(datatable_url,datatable_columns);
        
        var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        responsive: true,
        ajax: {
                  url: datatable_url,
                  data : function(d){
                          d.store_id = $("#store_reports option:selected").val(); 
                          d.from_date= start.format('YYYY/MM/DD');
                          d.to_date= end.format('YYYY/MM/DD');
                      }
              },
        columns: datatable_columns,
        order: [],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };
  
            total = api.column(1, { page: 'current'} ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
           // Update footer
            $( api.column(1).footer() ).html( '<b>Total Amount: '+ total +'</b>' );
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
