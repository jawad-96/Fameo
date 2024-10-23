@extends('admin.layouts.app')



@section('content')

<section id="main-content" >

    <section class="wrapper">

        <div class="row">

            <div class="col-md-12">

                <!--breadcrumbs start -->

                <ul class="breadcrumb">

                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>

                    <li class="active">Password Settings</li>

                </ul>

                <!--breadcrumbs end -->

            </div>

        </div>                

        

            {!! Form::open(['url' => 'admin/change-password','data-toggle' => 'validator','data-disable' => 'false', 'class' => 'form-horizontal', 'files' => true]) !!}



                <div class="row">            

                    <div class="col-lg-12">

                        <section class="panel">

                            <header class="panel-heading">Change Password</header>

                            <div class="panel-body">

                                <div class="position-center">                    



                                    <div class="form-group {{ $errors->has('current_password') ? 'has-error' : ''}}">

                                        {!! Form::label('current_password', 'Current Password', ['class' => 'col-md-4 control-label required-input']) !!}

                                        <div class="col-md-6">

                                            {!! Form::password('current_password', ['class' => 'form-control','placeholder' => 'Current Password','required'=>'required']) !!}

                                            {!! $errors->first('current_password', '<p class="help-block">:message</p>') !!}

                                            <div class="help-block with-errors"></div>

                                        </div>

                                    </div>

                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">

                                        {!! Form::label('password', 'New Password', ['class' => 'col-md-4 control-label required-input']) !!}

                                        <div class="col-md-6">

                                            {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Password','required'=>'required','data-minlength' => 6]) !!}

                                            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}

                                            <div class="help-block with-errors"></div>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-4 control-label required-input']) !!}

                                        <div class="col-md-6">

                                            {!! Form::password('password_confirmation', ['class' => 'form-control','placeholder' => 'Confirm Password','required' => 'required','data-match'=>'#password']) !!}                        

                                            {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}

                                            <div class="help-block with-errors"></div>

                                        </div>

                                    </div>                    



                                    <div class="form-group">

                                        <div class="col-lg-offset-2 col-lg-10">

                                            {!! Form::submit('Change Password', ['class' => 'btn btn-info pull-right']) !!}

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





