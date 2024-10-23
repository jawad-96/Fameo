@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Newsletters</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>    
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Newsletters
                        @can('add newsletters')
                        <span class="tools pull-right">
                            <a href="{{ url('admin/newsletters/create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Add New Newsletter">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                         </span>
                         @endcan
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th> Subject </th>
                                    <th> From </th>
                                    <th> Status </th>
                                    <th> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th> Subject </th>
                                    <th> From </th>
                                    <th> Status </th>
                                    <th> Actions </th>
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
        var timeOutId = 0;
        var offset    = 0;
        var limit     = 2;
        var total     = 0;
        var check_session = "<?php if (Session::has('newsletter_action'))
                                {echo session('newsletter_action');}?>";

        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{url('admin/newsletters')}}",
            columns: [
            {data: 'subject', name: 'subject'},
            {data: 'from', name: 'from'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "initComplete": function(settings, json) {
                if(check_session){            
                    $('#datatable').find(".test-"+check_session).click();
                }
            }
        });   

        var $reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );

        //change status
        
        $("body").on("click",".change_status", function(){
            var id      = $(this).attr("data-id");
            var status  = $(this).attr("data-status");
            var url= "{{url('admin/newsletter_action')}}"+'/'+id;
            if(status == 'completed')
                return false;

            var send_newsletter = function () {
                $.ajax({
                    url:url,
                    type:"put",
                    data:{status:status,id:id, offset:offset, limit: limit},
                    success:function (res) {
                        if (res.total) {
                            if(res.offset == 0){
                                $reload_datatable.fnDraw();
                            }
                            if(res.offset <= res.total-1){
                                total = res.total;
                                offset = offset+2;
                                var text = "Sending " + offset + " of " + (total);
                                $("#send_status").html(text);
                                timeOutId = setTimeout(send_newsletter, 1000);//set the timeout again
                            } else {
                                $reload_datatable.fnDraw();
                            }
                        } else {
                            $reload_datatable.fnDraw();
                        }
                    }//.... end of success.

                });//..... end of ajax() .....//
            }
            send_newsletter(); 
        });//end of change status

        //delete record
        var reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );
        $('#datatable').on('click', '.btn-delete', function (e) { 
            e.preventDefault();
            var id = $(this).attr('id');
            var url= "{{url('admin/newsletters')}}"+'/'+id;
            var method = "delete";
            
            remove_record(url,reload_datatable,method);
        }); //end of delete
    }); //..... end of ready() .....//

    function newsletter_update(){
        var text = "Sending " + offset + " of " + (total);
        $("#import_message").html(text);
    }
    
</script>

@endsection