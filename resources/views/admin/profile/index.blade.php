@extends('admin.layouts.app')



@section('content')

<section id="main-content" >

    <section class="wrapper">

        <div class="row">

            <div class="col-md-12">

                <!--breadcrumbs start -->

                <ul class="breadcrumb">

                   <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>

                    <li class="active">Profile</li>

                </ul>

                <!--breadcrumbs end -->

            </div>

        </div>                

            

        {!! Form::model($profile, [

                    'method' => 'POST',

                    'url' => ['admin/profile/update'],

                    'class' => 'form-horizontal',

                    'data-toggle' => 'validator',

                    'data-disable' => 'false',

                    'files' => true

                ]) !!}                            



                <div class="row">            

                    <div class="col-lg-12">

                        <section class="panel">

                        <header class="panel-heading">Profile</header>

                            <div class="panel-body">

                                <div class="position-center">



                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">

                                        {!! Form::label('name', 'Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}

                                        <div class="col-lg-9">

                                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Company Name','required' => 'required']) !!}

                                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}

                                            <div class="help-block with-errors"></div>

                                        </div>

                                    </div>

                                    

                                    <div class="form-group">

                                        {!! Form::label('name', 'Email', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}

                                        <div class="col-lg-9">

                                            {!! Form::text('', @$profile->email, ['class' => 'form-control','placeholder' => 'Company Email','disabled' => 'disabled']) !!}

                                        </div>

                                    </div>



                                    <div class="form-group">

                                        <div class="col-lg-offset-2 col-lg-10">

                                            {!! Form::submit('Update', ['class' => 'btn btn-info pull-right']) !!}

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



@endsection



@section('scripts')

<script type="text/javascript">

    $(document).ready(function(){

        $("#country").select2();        

    });

</script>

@endsection



