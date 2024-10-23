
@extends('admin.layouts.app')

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ url('admin/products') }}">Products</a></li>
                    <li class="active">Update</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
            
        @php($current_tab = app('request')->input('tab'))
        @switch($current_tab)
            @case(1)
                @php($url = '/admin/products/'.Hashids::encode($product->id))
                @break

            @case(2)
                @php($url = '/admin/products/update-store/'.Hashids::encode($product->id))
                @break

            @default
                @php($url = '/admin/products/'.Hashids::encode($product->id))
        @endswitch 
        
        @if($current_tab == 3)
        
            @if($product->is_variants == 1)
                @include ('admin.products.variant_form')
            @endif
            
        @else  
            
            {!! Form::model($product, [
                'method' => 'PATCH',
                'url' => $url,
                'files' => true,
                'data-toggle' => 'validator',
                'data-disable' => 'false',
                'id' =>'category_form'
                ]) !!}
                
                @include ('admin.products.form', ['submitButtonText' => 'Update'])

            {!! Form::close() !!}
            
        @endif
    </section>
</section>

@endsection