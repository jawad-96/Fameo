@extends('admin.layouts.app')



@section('style')
    <style>
        .cke_inner{border: 1px solid #e2e2e4 !important;border-radius: 4px !important;}
    </style>    
@endsection



@section('content')



<section id="main-content" >

    <section class="wrapper">

        <div class="row">

            <div class="col-md-12">

                <!--breadcrumbs start -->

                <ul class="breadcrumb">

                   <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>

                    <li class="active">Settings</li>

                </ul>

                <!--breadcrumbs end -->

            </div>

        </div>                

            

        {!! Form::open(['url' => 'admin/send-email', 'class' => 'form-horizontal']) !!}

                <div class="row">            
                    <div class="col-lg-12">
                        <section class="panel">
                        <header class="panel-heading">Send Email</header>
                            <div class="panel-body">
                                <div class="position-center">
                                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
                                        {!! Form::label('subject', 'Subject', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('subject', null, ['class' => 'form-control','required' => 'required']) !!}
                                            {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-8">
                                            {!! Form::checkbox('select_all', 1, null,['id'=>'select_all']) !!} <b>Select All Customers</b>                    
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : ''}}">
                                        {!! Form::label('user_ids', 'Select Customers', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('user_ids[]', $users, null, ['class' => 'form-control select2', 'id' => 'user_ids', 'multiple', 'required' => 'required']) !!}
                                            {!! $errors->first('user_ids', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                                        {!! Form::label('body', 'Email Body', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('body', null, ['class' => 'form-control','required' => 'required','id' => 'body']) !!}
                                            {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>


                                    <div class="form-group">

                                        <div class="col-lg-offset-2 col-lg-10">

                                            {!! Form::submit('Send Email', ['class' => 'btn btn-info pull-right']) !!}

                                        </div>

                                    </div>



                                </div> 

                            </div>

                        </section>

                    </div>

                </div>

            {!! Form::close() !!}

            

    </section>

</section>




                </div>

            </div>

        </div>



        <!--     </section>     

</div> -->

 

@endsection



@section('scripts')

 <!--<script type="text/javascript" src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>--> 
<script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        
        $("#select_all").click(function(){
            $("#user_ids > option").prop("selected", $("#select_all").is(':checked'));
            $("#user_ids").trigger("change");
        });
        
         CKEDITOR.replace( 'body',{            
             removePlugins: 'elementspath,magicline',
             resize_enabled: false,
             allowedContent: true,
             enterMode: CKEDITOR.ENTER_BR,
             shiftEnterMode: CKEDITOR.ENTER_BR,
             toolbar: [
                 [ 'Source','-','Bold','-','Italic','-','Underline'],
             ],
         });

    });

</script>

@endsection



