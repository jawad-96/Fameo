
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Promotion</header>
            <div class="panel-body">
                <div class="position-center">                                                                                                              
                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name', 'Promotion Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                    <div class="col-lg-9">
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Promotion Name','required' => 'required']) !!}
                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>                                                                                                  

                <div class="form-group {{ $errors->has('product_ids') ? 'has-error' : ''}}">
                    {!! Form::label('product_ids', 'Select Products', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                    <div class="col-lg-9">
                        {!! Form::select('product_ids[]', $products, @$product_ids, ['class' => 'form-control select2','multiple']) !!}
                        {!! $errors->first('product_ids', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>    
                </div>   

                <div class="form-group {{ $errors->has('start_time') ? 'has-error' : ''}}">
                    {!! Form::label('start_time', 'Promotion Start Time', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                    <div class="col-lg-9">
                        {!! Form::text('start_time', null, ['class' => 'form-control','placeholder' => 'Promotion Start Time' , 'required','autocomplete'=>'off']) !!}
                        {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>                    

                <div class="form-group {{ $errors->has('end_time') ? 'has-error' : ''}}">
                    {!! Form::label('end_time', 'Promotion End Time', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                    <div class="col-lg-9">
                        {!! Form::text('end_time', null, ['class' => 'form-control','placeholder' => 'Promotion End Time' , 'required','autocomplete'=>'off']) !!}
                        {!! $errors->first('end_time', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-info pull-right']) !!}
                    </div>
                </div>
                </div>
            </div>
        </section>

    </div>
</div>


@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#start_time').datetimepicker();
        $('#end_time').datetimepicker();
    });
</script>
@endsection