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

            

        {!! Form::open(['url' => 'admin/settings/update', 'class' => 'form-horizontal', 'files' => true]) !!}

                <div class="row">            
                    <div class="col-lg-12">
                        <section class="panel">
                        <header class="panel-heading">General Settings</header>
                            <div class="panel-body">
                                <div class="position-center">
                                    <div class="form-group {{ $errors->has('site_title') ? 'has-error' : ''}}">
                                        {!! Form::label('site_title', 'Site Title', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('site_title', settingValue('site_title'), ['class' => 'form-control','required' => 'required']) !!}
                                            {!! $errors->first('site_title', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('phone', 'Phone', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('phone', settingValue('phone'), ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('address', 'Address', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('address', settingValue('address'), ['class' => 'form-control']) !!}
                                        </div>
                                    </div>


                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                                        {!! Form::label('email', 'Default Email', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::email('email', settingValue('email'), ['class' => 'form-control','required' => 'required']) !!}
                                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>                                                                                                        
                                    <div class="form-group {{ $errors->has('currency_id') ? 'has-error' : ''}}">
                                        {!! Form::label('currency_id', 'Default Currency', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('currency_id', getCurrencyDropdown(), settingValue('currency_id'), ['class' => 'form-control select2','required' => 'required']) !!}
                                            {!! $errors->first('currency_id', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div> 

                                    <div class="form-group {{ $errors->has('store_id') ? 'has-error' : ''}}">
                                        {!! Form::label('store_id', 'Default Store', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('store_id', getStoresDropdown(), settingValue('store_id'), ['class' => 'form-control select2','required' => 'required']) !!}
                                            {!! $errors->first('store_id', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                     <div class="form-group {{ $errors->has('tax_id') ? 'has-error' : ''}}">
                                        {!! Form::label('tax_id', 'Default Tax', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('tax_id', getTaxRatesDropdown(), settingValue('tax_id'), ['class' => 'form-control select2','required' => 'required']) !!}
                                            {!! $errors->first('tax_id', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group {{ $errors->has('shipping_id') ? 'has-error' : ''}}">
                                        {!! Form::label('shipping_id', 'Default Shipping', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('shipping_id', getShippingDropdown(), settingValue('shipping_id'), ['class' => 'form-control select2','required' => 'required']) !!}
                                            {!! $errors->first('shipping_id', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('wholesaler_percentage') ? 'has-error' : ''}}">
                                        {!! Form::label('wholesaler_percentage', 'Wholesaler Percentage', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::number('wholesaler_percentage', settingValue('wholesaler_percentage'), ['class' => 'form-control','required' => 'required','min' => '0']) !!}
                                            {!! $errors->first('wholesaler_percentage', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('wholesaler_quantity') ? 'has-error' : ''}}">
                                        {!! Form::label('wholesaler_quantity', 'Wholesaler Max Quantities', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::number('wholesaler_quantity', settingValue('wholesaler_quantity'), ['class' => 'form-control','required' => 'required','min' => '0']) !!}
                                            {!! $errors->first('wholesaler_quantity', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
                                        
                                        {!! Form::label('site_logo', 'Site Logo', ['class' => 'col-md-4 control-label required-input']) !!}                        
                                        
                                        <div class="col-md-8">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px;">
                                                    @php($site_logo = settingValue('site_logo'))
                                                    <img src="{{ checkImage('settings/'. $site_logo) }}" alt="" />                                                                                  
                                                </div>
                                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                <div>
                                                    <span class="btn btn-white btn-file">
                                                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                                    <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                                    <input type="file" class="default" name="site_logo" accept="image/*" />
                                                    </span>
                                                    <a href="#" class="btn btn-info fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
                                                </div>
                                                {!! $errors->first('site_logo', '<p class="help-block">:message</p>') !!}
                                                <div class="help-block with-errors"></div>
                                            </div>                        
                                        </div>
                                    </div>

                                     <!-- <div class="form-group {{ $errors->has('instructions') ? 'has-error' : ''}}">

                                        {!! Form::label('instructions', 'Instructions', ['class' => 'col-md-3 control-label required-input']) !!}

                                        <div class="col-md-9">

                                            {!! Form::textarea('instructions', settingValue('instructions'), ['class' => 'form-control ckeditor','required' => 'required']) !!}

                                            {!! $errors->first('instructions', '<p class="help-block">:message</p>') !!}

                                        </div>

                                    </div> -->


                                    @can('edit settings')
                                    <div class="form-group">

                                        <div class="col-lg-offset-2 col-lg-10">

                                            {!! Form::submit('Update', ['class' => 'btn btn-info pull-right']) !!}

                                        </div>

                                    </div>
                                    @endcan



                                </div> 

                            </div>

                        </section>

                    </div>

                </div>

                 <div class="row">            
                    <div class="col-lg-12">
                        <section class="panel">
                        <header class="panel-heading">API Settings</header>
                            <div class="panel-body">
                                <div class="position-center">
                                    <div class="form-group {{ $errors->has('paypal_client_id') ? 'has-error' : ''}}">
                                        {!! Form::label('paypal_client_id', 'Paypal Client ID', ['class' => 'col-md-4 control-label required-input']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('paypal_client_id', settingValue('paypal_client_id'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('paypal_client_id', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>                                                                                            

                                    @can('edit settings')
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            {!! Form::submit('Update', ['class' => 'btn btn-info pull-right']) !!}
                                        </div>
                                    </div>
                                    @endcan


                                </div> 

                            </div>

                        </section>

                    </div>

                </div>


                <div class="row">            
                    <div class="col-lg-12">
                        <section class="panel">
                        <header class="panel-heading">Social Media Settings</header>
                            <div class="panel-body">
                                <div class="position-center">
                                    <div class="form-group {{ $errors->has('facebook') ? 'has-error' : ''}}">
                                        {!! Form::label('facebook', 'Facebook', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('facebook', settingValue('facebook'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('twitter') ? 'has-error' : ''}}">
                                        {!! Form::label('twitter', 'Twitter', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('twitter', settingValue('twitter'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('twitter', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('ebay') ? 'has-error' : ''}}">
                                        {!! Form::label('ebay', 'Ebay', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('ebay', settingValue('ebay'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('ebay', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('amazon') ? 'has-error' : ''}}">
                                        {!! Form::label('amazon', 'Amazon', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('amazon', settingValue('amazon'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('amazon', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('instagram') ? 'has-error' : ''}}">
                                        {!! Form::label('instagram', 'Instagram', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('instagram', settingValue('instagram'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('instagram', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>    

                                    <div class="form-group {{ $errors->has('pinterest') ? 'has-error' : ''}}">
                                        {!! Form::label('pinterest', 'Pinterest', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('pinterest', settingValue('pinterest'), ['class' => 'form-control']) !!}
                                            {!! $errors->first('pinterest', '<p class="help-block">:message</p>') !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>                                                                                            
                                    
                                    @can('edit settings')
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            {!! Form::submit('Update', ['class' => 'btn btn-info pull-right']) !!}
                                        </div>
                                    </div>
                                    @endcan


                                </div> 

                            </div>

                        </section>

                    </div>

                </div>

            {!! Form::close() !!}

            

    </section>

</section>

 

                    {!! Form::close() !!}



                </div>

            </div>

        </div>



        <!--     </section>     

</div> -->

 

@endsection



@section('scripts')

<!-- <script type="text/javascript" src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script> -->

<script type="text/javascript">

    $(document).ready(function(){

        // CKEDITOR.replace( 'instructions',{            

        //     removePlugins: 'elementspath,magicline',

        //     resize_enabled: false,

        //     allowedContent: true,

        //     enterMode: CKEDITOR.ENTER_BR,

        //     shiftEnterMode: CKEDITOR.ENTER_BR,

        //     toolbar: [

        //         [ 'Source','-','Bold','-','Italic','-','Underline'],

        //     ],

        // });

    });

</script>

@endsection



