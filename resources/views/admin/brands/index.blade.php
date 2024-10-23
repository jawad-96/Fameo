@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Brands</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Brands
                        @can('add brands')
                        <span class="tools pull-right">
                            <a href="{{ url('admin/brands/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New Brand">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                         </span>
                         @endcan
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                @if(auth()->user()->can('edit brands') || auth()->user()->can('delete brands'))
                                <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                @if(auth()->user()->can('edit brands') || auth()->user()->can('delete brands'))
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
      
@endsection


@section('scripts')
<script type="text/javascript">
$("document").ready(function () {
    var datatable_url = "{{url('admin/brands')}}";
    var datatable_columns = [
        {data: 'image', name: 'image',width: '15%'},
        {data: 'name', name: 'name'},   
        @if(auth()->user()->can('edit brands') || auth()->user()->can('delete brands'))
        {data: 'action', name: 'action', width: '10%',orderable: false, searchable: false}
        @endif
        ];
        
        create_datatables(datatable_url,datatable_columns);
      });            
</script>
@endsection                            
