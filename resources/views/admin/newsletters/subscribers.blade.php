@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Subscribers</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>    
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Subscribers
                    </header>
                    <div class="panel-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Date Subscribed</th>                       
                                    <th>Is Subscribed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Email</th>
                                    <th>Date Subscribed</th>                       
                                    <th>Is Subscribed</th>
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
      var datatable_url = "{{url('admin/subscribers')}}";
      var datatable_columns = [
          {data: 'email', width: "40%"},
          {data: "created_at",
            render: function(d){
              return moment(d).format("MM/DD/YYYY");
            }
          , width: "40%"},
          {data: 'action', width: "20%", orderable: false, searchable: false}
      ];
      create_datatables(datatable_url,datatable_columns);

        var $reload_datatable = $("#datatable").dataTable( { bRetrieve : true } );

        //change status
        $("body").on("click",".click", function(){
          var id = $(this).attr("id");
          var url= "{{url('admin/is_subscribed')}}"+'/'+id;
          if ($(this).is(':checked')) {
            var status = 1;
          } else {
            var status = 0;
          }

          $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
          url:url,
          type:"put",
          data:{is_subscribed:status,id:id},
          success:function (res) {
            if (res == 'true') {
              $reload_datatable.fnDraw();
            }
          }//.... end of success.

          });//..... end of ajax() .....//
        });//end of change status
    }); //..... end of ready() .....//
</script>

@endsection