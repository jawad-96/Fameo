@extends('admin.layouts.app')

@section('content')

<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ url('admin/newsletters') }}">Newsletters</a></li>
                    <li class="active">Add</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
            {!! Form::open(['url' => 'admin/newsletters', 'data-toggle' => 'validator','data-disable' => 'false', 'class' => 'form-horizontal', 'files' => true]) !!}

                @include ('admin.newsletters.form')

            {!! Form::close() !!}
            
    </section>
</section>

@endsection


