
@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ url('admin/categories') }}">Categories</a></li>
                    <li class="active">Update</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
            {!! Form::model($category, [
                'method' => 'PATCH',
                'url' => ['/admin/categories', Hashids::encode($category->id)],
                'class' => 'form-horizontal',
                'files' => true,
                'data-toggle' => 'validator',
                'data-disable' => 'false',
                'id' =>'category_form'
                ]) !!}
                
                @include ('admin.categories.form', ['submitButtonText' => 'Update'])

            {!! Form::close() !!}
            
    </section>
</section>

@endsection