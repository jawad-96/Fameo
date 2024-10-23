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
                    <li class="active">Update</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
            {!! Form::model($newsletter, [
                'method' => 'PATCH',
                'url' => ['admin/newsletters', Hashids::encode($newsletter->id)],
                'class' => 'form-horizontal',
                'files' => true,
                'data-toggle' => 'validator',
                'data-disable' => 'false',
                ]) !!}
                
                @include ('admin.newsletters.form', ['submitButtonText' => 'Update'])    

            {!! Form::close() !!}
            
    </section>
</section>

@endsection
