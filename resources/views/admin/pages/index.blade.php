@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Pages</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>    
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Pages
                        @can('add pages')
                        <span class="tools pull-right">
                            <a href="{{ url('admin/pages/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New Page">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                         </span>
                         @endif
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th> Title </th>
                                    <th> Meta Title </th>
                                    <th> Meta Keywords </th>
                                    @if(auth()->user()->can('edit pages') || auth()->user()->can('delete pages'))
                                    <th> Actions </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th> Title </th>
                                    <th> Meta Title </th>
                                    <th> Meta Keywords </th>
                                    @if(auth()->user()->can('edit pages') || auth()->user()->can('delete pages'))
                                    <th> Actions </th>
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
<script>
    $("document").ready(function () {
        var datatable_url = "{{url('admin/pages')}}";
        var datatable_columns = [
            {data: 'title'},
            {data: 'meta_title'},
            {data: 'meta_keywords', width: "40%"},
            @if(auth()->user()->can('edit pages') || auth()->user()->can('delete pages'))
            {data: 'action', width: "10%", orderable: false, searchable: false}
            @endif
        ];
        create_datatables(datatable_url,datatable_columns);
    });

</script>
@endsection