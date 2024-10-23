@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
      <h1>Password Settings</h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>           
        <li class="active">Password Settings</li>
    </ol>
</section>

<section class="content">
    <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <div class="box box-info">

        {!! Form::open(['url' => '/admin/settings/change-password', 'class' => 'form-horizontal', 'files' => true]) !!}

        <div class="box-header with-border">
            <h3 class="box-title">Change Password</h3>
        </div><!-- /.box-header -->                
        <div class="box-body">

        
        <div class="form-group {{ $errors->has('current_password') ? 'has-error' : ''}}">
            {!! Form::label('current_password', 'Current Password', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::password('current_password', ['class' => 'form-control']) !!}
                {!! $errors->first('current_password', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
            {!! Form::label('password', 'New Password', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::password('password', ['class' => 'form-control']) !!}
                {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}                        
            </div>
        </div>
        
    </div><!-- /.box-body -->
    <div class="box-footer"> 
    {!! Form::submit('Change Password', ['class' => 'btn btn-info pull-right']) !!}
    </div><!-- /.box-footer -->

    {!! Form::close() !!}

    </div>
    </div>
    </div>

</section>      
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $.uploadPreview({
        input_field: "#image-upload",   // Default: .image-upload
        preview_box: "#image-preview",  // Default: .image-preview
        label_field: "#image-label",    // Default: .image-label
        label_default: "Choose Logo",   // Default: Choose File
        label_selected: "Change Logo",  // Default: Change File
        no_label: false                 // Default: false
        });

    });
</script>
@endsection

