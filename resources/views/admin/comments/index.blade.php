@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Feedback</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Feedback
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Product</th>
                                <th>Comment</th>
                                @if(auth()->user()->can('delete_comments'))
                                <th>Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Product</th>
                                <th>Comment</th>
                                @if(auth()->user()->can('delete_comments'))
                                <th>Actions</th>
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
      
@endsection


@section('scripts')
<script type="text/javascript">
$("document").ready(function () {
    var datatable_url = "{{url('admin/product-feedback')}}";
    var datatable_columns = [
        {data: 'name',width: '10%'},
        {data: 'prodcut_name'},
        {data: 'description'},
        @if(auth()->user()->can('delete_comments'))
        {data: 'action', width: '10%',orderable: false, searchable: false}
        @endif
        
        ];
        
        create_datatables(datatable_url,datatable_columns);
  });            
</script>
@endsection                            
