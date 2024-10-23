@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Faqs</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>    
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Faqs
                        @can('add faqs')
                        <span class="tools pull-right">
                            <a href="{{ url('admin/faqs/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New FAQ">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                         </span>
                         @endcan
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th> Question </th>
                                    <th> Display Order </th>
                                    @if(auth()->user()->can('edit faqs') || auth()->user()->can('delete faqs'))
                                    <th> Actions </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th> Question </th>
                                    <th> Display Order </th>
                                    @if(auth()->user()->can('edit faqs') || auth()->user()->can('delete faqs'))
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
        var datatable_url = "{{url('admin/faqs')}}";
        var datatable_columns = [
            {data: 'question'},
            {data: 'ordering', className: 'text-center', width: "15%"},
            @if(auth()->user()->can('edit faqs') || auth()->user()->can('delete faqs'))
            {data: 'action', width: "10%", orderable: false, searchable: false}
            @endif
        ];
        create_datatables(datatable_url,datatable_columns);
    });
</script>

@endsection