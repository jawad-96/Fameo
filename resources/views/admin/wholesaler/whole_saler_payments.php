@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Wholesaler Payments</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Wholesaler Payments
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
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
    var datatable_url = "{{url('wholesaler/payments')}}"+'/'+id;
    var datatable_columns = [
        {data: 'date'},
        {data: 'amount'},
        ];
        
        create_datatables(datatable_url,datatable_columns);
    
    });
</script>
@endsection                            
