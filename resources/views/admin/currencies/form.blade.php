
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Currency</header>
            <div class="panel-body">
                <div class="position-center">                    
                    
                    <div class="form-group {{ $errors->has('code') ? 'has-error' : ''}}">
                        {!! Form::label('code', 'Code', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('code', null, ['class' => 'form-control','placeholder' => 'Code','required' => 'required']) !!}
                            {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>                                                                                                                      
                    
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Name','required' => 'required']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>                                                                                                                      
                   
                    <div class="form-group {{ $errors->has('symbol') ? 'has-error' : ''}}">
                        {!! Form::label('symbol', 'Symbol', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('symbol', null, ['class' => 'form-control','placeholder' => 'Symbol','required' => 'required']) !!}
                            {!! $errors->first('symbol', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>                                                                                                                      
                    
                    <div class="form-group {{ $errors->has('direction') ? 'has-error' : ''}}">
                        {!! Form::label('direction', 'Direction', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::select('direction', [''=>'Select Direction','left'=>'Left','right'=>'Right'],null, ['class' => 'form-control','required' => 'required']) !!}
                            {!! $errors->first('direction', '<p class="help-block">:message</p>') !!}
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