
@extends('admin.layouts.app')

@section('style')
    <style>
        .cke_inner{border: 1px solid #e2e2e4 !important;border-radius: 4px !important;}
        .select2-container{width:100% !important;}
    </style>    
@endsection

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
            
            {!! Form::model($product, [
                'method' => 'PATCH',
                'url' => ['admin/products/update-variant-product', Hashids::encode($product->id)],
                'files' => true,
                'data-toggle' => 'validator',
                'data-disable' => 'false',
                'id' =>'variant_product_form',
                'class' =>'form-horizontal',
                ]) !!}

 
                <div class="row">            
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">Product Information </header>
                            <div class="panel-body">                                                      
                                <div class="position-center">                                

                                    <div class="form-group {{ $errors->has('code') ? 'has-error' : ''}}">
                                        {!! Form::label('code', 'Product Code', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            <div class="input-group">
                                                {!! Form::text('code', null, ['class' => 'form-control','placeholder'=>'Product Code','required' => 'required']) !!}
                                                <span class="input-group-addon pointer" id="genrate_random_number"><i class="fa fa-random"></i></span>
                                            </div>
                                            {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group {{ $errors->has('ean_number') ? 'has-error' : ''}}">
                                        {!! Form::label('ean_number', 'EAN Number', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            {!! Form::text('ean_number', null, ['class' => 'form-control','placeholder'=>'EAN Number','required' => 'required']) !!}
                                            {!! $errors->first('ean_number', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                        
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                                        {!! Form::label('name', 'Product Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>'Product Name','required' => 'required']) !!}
                                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>    
                                    </div>
                                    <div class="form-group {{ $errors->has('prefix') ? 'has-error' : ''}}">
                                        {!! Form::label('prefix', 'Category Prefix', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                                        <div class="col-lg-9">
                                           {!! Form::select('prefix', $categories,null, ['class' => 'form-control select2']) !!}
                                            {!! $errors->first('prefix', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>     
                                        </div>    
                                    </div>
                                    <div class="form-group {{ $errors->has('sku') ? 'has-error' : ''}}">
                                        {!! Form::label('sku', 'SKU', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            <div class="input-group">
                                                {!! Form::text('sku', null, ['class' => 'form-control','placeholder'=>'SKU','required' => 'required']) !!}
                                                <span class="input-group-addon pointer" id="genrate_random_number_sku"><i class="fa fa-random"></i></span>
                                            </div>
                                            {!! $errors->first('sku', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>    
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            {!! Form::checkbox('is_main_price', 1, null,['id'=>'is_main_price']) !!} <b>Price same as main product</b>                    
                                        </div>
                                    </div>

                                    <div class="form-group is_main_price {{ $errors->has('cost') ? 'has-error' : ''}}">
                                        {!! Form::label('cost', 'Product Cost', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            {!! Form::text('cost', null, ['class' => 'form-control','min' => '0','placeholder'=>'Product Cost','required' => 'required']) !!}
                                            {!! $errors->first('cost', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>    
                                    </div>

                                    <div class="form-group is_main_price {{ $errors->has('price') ? 'has-error' : ''}}">
                                        {!! Form::label('price', 'Product Price', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            {!! Form::text('price', null, ['class' => 'form-control','placeholder'=>'Product Price','min' => '0','required' => 'required']) !!}
                                            {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>   
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            {!! Form::checkbox('is_main_tax', 1, null,['id'=>'is_main_tax']) !!} <b>Tax same as main product</b>                    
                                        </div>
                                    </div>
                                    
                                    <div class="form-group is_main_tax {{ $errors->has('tax_rate_id') ? 'has-error' : ''}}">
                                        {!! Form::label('tax_rate_id', 'Product Tax', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                        <div class="col-lg-9">
                                            {!! Form::select('tax_rate_id', getTaxRatesDropdown(),null, ['class' => 'form-control select2','required' => 'required']) !!}
                                            {!! $errors->first('tax_rate_id', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div> 
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('tags') ? 'has-error' : ''}}">
                                        {!! Form::label('tags', 'Tags', ['class' => 'col-lg-3 col-sm-3 control-label ']) !!}
                                        <div class="col-lg-9">
                                            {!! Form::textarea('tags', (isset($product)?$product->product_tags->pluck('name')->implode(','):'') , ['class' => 'form-control']) !!}
                                            {!! $errors->first('tags', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>    
                                    </div>                                                                
                                    
                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    
                                       {!! Form::label('image', 'Profile Image', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}                        

                                   <div class="col-md-9">
                                       <div class="fileupload fileupload-new" data-provides="fileupload">
                                           <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="{{ getProductDefaultImage($product->id, true) }}" alt="" />                                              
                                           </div>
                                           <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                           <div>
                                               <span class="btn btn-white btn-file">
                                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                               <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                               <input type="file" class="default" name="image" accept="image/*" />
                                               </span>
                                               <a href="#" class="btn btn-info fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
                                           </div>
                                           {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                                           <div class="help-block with-errors"></div>
                                       </div>                        
                                   </div>
                               </div>
                                    
                                </div>  
                        </div>
                        </section>
                    </div>

                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">Store Stocks </header>
                            <div class="panel-body">
                                <div class="position-center">

                                    @foreach($product->product->store_products as $store_product)
                                        <div class="form-group">
                                            {!! Form::label($store_product->store->name, $store_product->store->name, ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                            <div class="col-lg-9">
                                                {!! Form::number('store-product-'.$store_product->id, getStoreProductsData($product->id, $store_product->store->id,'quantity'), ['class' => 'form-control','placeholder' => 'Stock','required' => 'required']) !!}
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                    </div>
                    
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">Variants </header>
                            <div class="panel-body">
                                <div class="position-center">

                                    @foreach($product->product->product_attributes as $product_attribute)
                                        <div class="form-group">
                                            {!! Form::label($product_attribute->variant->name, $product_attribute->variant->name, ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                            <div class="col-lg-9">
                                                {!! Form::text('variant-name-'.$product_attribute->id, getVariantData($product->id, $product_attribute->variant_id, 'name'), ['class' => 'form-control','placeholder' => $product_attribute->variant->name,'required' => 'required']) !!}
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
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
        
        @if($product->is_main_price==1)
            $(".is_main_price").hide();                
            $("#price").prop("required",false);
            $("#cost").prop("required",false);
        @endif
        
        @if($product->is_main_tax==1)
            $(".is_main_tax").hide();         
        @endif
    
        $('#tags').tagsInput({width:'auto'});       
        
        $("#genrate_random_number").click(function(){
            var random_number = generateRandomNumber(6);
            $("#code").val(random_number);
            $("#code").blur();
            return false;
        });
        
        $("#genrate_random_number_sku").click(function(){
            var random_number = generateRandomNumber(4);
            var prefix = $("#prefix option:selected").val();

            var sku = $('#sku');
            if(sku.val()==""){
                if(prefix!=""){
                    sku.val(prefix+'-'+random_number);
                }else{
                    sku.val(random_number);
                }                
            }else{
                var skus = sku.val().split('-');
                if(skus.length==1){
                    if(prefix!=""){
                        sku.val(prefix+'-'+random_number);
                    }else{
                        sku.val(random_number);
                    }
                    
                }else{
                    
                    sku.val(skus[0]+'-'+random_number);
                        
                }            
            }

            //$("#sku").val(random_number);
            $("#sku").blur();
            return false;
        });
        
        $('#prefix').change(function(){
            var prefix = this.value;

            var sku = $('#sku');
            if(sku.val()==""){
                sku.val(prefix+'-');
            }else{
                var skus = sku.val().split('-');
                if(prefix==""){
                    if(skus.length==1){
                        sku.val(skus[0]);
                    }else{
                        sku.val(skus[1]);
                    } 
                }else{
                    if(skus.length==1){
                        sku.val(prefix+'-'+skus[0]);
                    }else{
                        sku.val(prefix+'-'+skus[1]);
                    }    
                }
                            
            }

            sku.focus();
        });
        
        $(document).on('click','#is_main_price',function() {             
            if ($(this).is(':checked')) {
                $(".is_main_price").hide();                
                $("#price").prop("required",false);
                $("#cost").prop("required",false);
            }else{
                $(".is_main_price").show();
                $("#price").prop("required",true);
                $("#cost").prop("required",true);
            } 
            
            $('#variant_product_form').validator('update');
        });
        
        $(document).on('click','#is_main_tax',function() {             
            if ($(this).is(':checked')) {
                $(".is_main_tax").hide();         
            }else{
                $(".is_main_tax").show();
            }             
        });
        
       
});  
           
</script>
@endsection