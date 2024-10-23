@extends('admin.layouts.app')

@section('content')
 <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Record Comments</h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>            
            <li class="active">Record Comments</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              
              <!-- DIRECT CHAT -->
                  <div class="box box-warning direct-chat direct-chat-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title">Record Comments</h3>
                      <div class="box-tools pull-right">                        
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <!-- Conversations are loaded here -->
                      <div class="direct-chat-messages">
                          
                        @foreach($comments as $comment)  
                            <!-- Message. Default to the left -->
                            <div class="direct-chat-msg">
                              <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">{{ $comment->user->name }}</span>                                
                                @if(empty($comment->deleted_at))
                                    <span title="Remove" data-id="{{ $comment->id }}" class="direct-chat-timestamp pull-right deleteBtn" style="cursor: pointer;margin-top: -10px;color: #d43737;"><i class="fa fa-times"></i></span>
                                    <span title="Edit" class="direct-chat-timestamp pull-right edit_btn" style="cursor: pointer;margin-top: -10px;color: #0a882c;margin-right: 3px;"><i class="fa fa-pencil"></i></span>
                                @endif
                                
                                <span class="direct-chat-timestamp pull-right">
                                    {{ date('d M H:m a', strtotime($comment->created_at)) }}
                                    @if(!empty($comment->deleted_at))
                                    - <b class="text-danger">{{ date('d M H:m a', strtotime($comment->deleted_at)) }}</b>
                                    @endif    
                                </span>
                              </div><!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{ checkImage('users/thumbs/'. $comment->user->profile_image) }}" alt="message user image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text"
                                   @if(!empty($comment->deleted_at))
                                    {{ 'style=background:#f3ccd9;border-color:#d43737;' }}
                                    @endif
                                >
                                @if(!empty($comment->deleted_at))
                                    <del>{{ $comment->comment }}</del>
                                @else
                                    {{ $comment->comment }}
                                @endif    
                              </div><!-- /.direct-chat-text -->
                              <div class="form-group edit_from" style="display: none;">
                                <div class="col-md-9">
                                    {!! Form::text('comment', $comment->comment, ['class' => 'form-control comment_data']) !!}                                    
                                </div>
                                <div class="col-md-2">
                                    {!! Form::button('Update', ['data-id' => $comment->id,'class' => 'btn btn-primary btn-sm update_btn']) !!}                                    
                                    {!! Form::button('x', ['class' => 'btn btn-info btn-sm  close_btn']) !!}                                    
                                </div>
                            </div>
                            </div><!-- /.direct-chat-msg -->                        
                        @endforeach
                      </div><!--/.direct-chat-messages-->
                     
                    </div><!-- /.box-body -->
                    
                  </div><!--/.direct-chat -->
                
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      
@endsection


@section('scripts')
<script type="text/javascript">
$("document").ready(function () {        

    $(document).on('click', '.edit_btn', function (e) {
        $(".direct-chat-text").show();
        $(".edit_from").hide();
        
        var parent_el = $(this).parents(".direct-chat-msg");
        parent_el.find(".direct-chat-text").hide();
        parent_el.find(".edit_from").show();
    });
    
    $(document).on('click', '.update_btn', function (e) {
        e.preventDefault();        
        
        var parent_el = $(this).parents(".direct-chat-msg");
        var comment_data = parent_el.find(".comment_data").val();
        var id = $(this).attr('data-id');
        var url= "{{ url('admin/records/update-comment') }}";
        
        $.ajax({
            type: "post",
            url: url,
            data:{id: id,comment: comment_data},
            complete:function (res) {  
                var result = res.responseJSON.result;
                  if(res.status == 200){                  
                    swal("Success Message", result.success, "success"); 
                    parent_el.find(".direct-chat-text").show();
                    parent_el.find(".direct-chat-text").text(comment_data);
                    parent_el.find(".edit_from").hide();                    
                }else{ 
                    swal("Cancelled", "Oops! Some thing want wrong.", "error");
                }
            }
        });
    });
    
    $(document).on('click', '.close_btn', function (e) {
        $(".direct-chat-text").show();
        $(".edit_from").hide();
    });
    
    $(document).on('click', '.deleteBtn', function (e) { 
        e.preventDefault();
        var id = $(this).attr('data-id');
        var url= "{{ url('admin/records/remove-comment') }}"+'/'+id;

        // confirm then
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm){
            if (isConfirm) {
                window.location.href = url;
            } else {
              swal("Cancelled", "Your imaginary record is safe :)", "error");
            }
          });
    }); 
  });
</script>
@endsection                            
