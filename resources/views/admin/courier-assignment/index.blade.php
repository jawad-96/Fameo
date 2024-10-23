@extends('admin.layouts.app')

@section('content')
    <section id="main-content" >
        <section class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                        <li class="active">Courier Asssignment</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Courier Asssignment
                            @can('add drop_shipper')
                                <span class="tools pull-right">
<!--                                <a href="{{ url('/admin/wholesaler/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New Wholesaler">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                </a>-->
                             </span>
                            @endcan
                        </header>
                        <div class="panel-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Cart No</th>
                                    <th>Status</th>

                                    @can('edit drop_shipper')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd"><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Cart No</th>
                                    <th>Status</th>

                                    @can('edit drop_shipper')
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
            var datatable_url = "{{url('admin/courier-assignment')}}";
            var datatable_columns = [
                {data: 'name'},
                {data: 'Cart No'},
                {data: 'is_active', orderable: false, searchable: false,width: "10%"},


                    @can('edit drop_shipper')
                {data: 'action', orderable: false, searchable: false,width: "10%"}
                @endcan
            ];

         create_datatables(datatable_url,datatable_columns);

        });
    </script>
@endsection
