<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} FAQ</header>
            <div class="panel-body">
                <div class="position-center">                    
                    <div class="form-group {{ $errors->has('question') ? 'has-error' : ''}}">
                        {!! Form::label('question', 'Question', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('question', null, ['class' => 'form-control', 'placeholder' => 'Question', 'required']) !!}
                            {!! $errors->first('question', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('answer') ? 'has-error' : ''}}">
                        {!! Form::label('answer', 'Answer', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::textarea('answer', null, ['class' => 'form-control answer', 'placeholder' => 'Answer', 'required']) !!}
                            {!! $errors->first('answer', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('ordering') ? 'has-error' : ''}}">
                        {!! Form::label('ordering', 'Display Order', ['class' => 'col-md-3 control-label required-input']) !!}
                        <div class="col-md-9">
                            {!! Form::number('ordering', null, ['class' => 'form-control','placeholder'=>'Display Order','required' => 'required','min'=>0]) !!}
                            {!! $errors->first('ordering', '<p class="help-block">:message</p>') !!}
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
