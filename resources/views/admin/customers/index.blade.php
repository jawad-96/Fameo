@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Customers</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
         <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">Customers</header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone#</th>
                                 <th>Address</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone#</th>
                                <th>Address</th>
                                <th>Action</th>
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

    var table;
    $("document").ready(function () {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{url('admin/customers')}}",
                data : function(d){
                    if($(".filter_by_store").val() != ''){
                        d.columns[3]['search']['value'] = $(".filter_by_store option:selected").text();
                    }                    
                    }
            }, 
            columns: [
                {data: 'profile_image', width: '10%',orderable: false, searchable: false},
                {data: 'name'},
                {data: 'email'},
                {data: 'mobile'},
                {data: 'address'},
                {data: 'action', width: '10%', orderable: false, searchable: false}
            ],
            "order": []
        });   
        
        // $("#datatable_length").append('{!! Form::select("type", getStoresFilterDropdown(), null, ["class" => "form-control input-sm filter_by_store","style"=>"margin-left: 20px;"]) !!}');
        
        // var reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );
        
        // $(document).on('change', '.filter_by_store', function (e) {
        //     reload_datatable.fnDraw();
        // });
    }); //..... end of ready() .....//
</script>

@endsection