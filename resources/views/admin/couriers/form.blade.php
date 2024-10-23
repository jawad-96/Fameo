
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Add' }} Courier Charges</header>
            <div class="panel-body">
                <div class="position-center">                    
                                                                                                                                                                        
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Name','required' => 'required']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>                                                                                                  
                    <div class="form-group {{ $errors->has('charges') ? 'has-error' : ''}}">
                        {!! Form::label('charges', 'Charges', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">£</button>
                                </span>
                                {!! Form::number('charges', null, ['class' => 'form-control','required' => 'required','min'=>0,'step'=>"0.01"]) !!}
                            </div>    
                            {!! $errors->first('charges', '<p class="help-block">:message</p>') !!}
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
        
    });
</script>
@endsection