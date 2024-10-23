@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Admins</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Admins
                        @can('add admins')
                            <span class="tools pull-right">
                                <a href="{{ url('/admin/admins/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New Admin">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                </a>
                             </span>
                        @endcan
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                @can('edit admins')
                                <th>Action</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                @can('edit admins')
                                <th>Action</th>
                                @endcan
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
    var datatable_url = "{{url('admin/admins')}}";
    var datatable_columns = [
        {data: 'name'},
        {data: 'email'},
        @can('edit admins')
        {data: 'action', orderable: false, searchable: false}
        @endcan        
        ];
        
        create_datatables(datatable_url,datatable_columns);
      });            
</script>
@endsection                            
