<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Page</header>
            <div class="panel-body">
                <div class="position-center">                    

                    <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title', 'required']) !!}
                            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('meta_title') ? 'has-error' : ''}}">
                        {!! Form::label('meta_title', 'Meta Title', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('meta_title', null, ['class' => 'form-control', 'placeholder' => 'Meta Title', 'required']) !!}
                            {!! $errors->first('meta_title', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('meta_keywords') ? 'has-error' : ''}}">
                        {!! Form::label('meta_keywords', 'Meta Keywords', ['class' => 'col-lg-3 
                        col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('meta_keywords', null, ['class' => 'form-control', 'placeholder' => 'Meta Keywords', 'required']) !!}
                            {!! $errors->first('meta_keywords', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('meta_description') ? 'has-error' : ''}}">
                        {!! Form::label('meta_description', 'Meta Description', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('meta_description', null, ['class' => 'form-control', 'placeholder' => 'Meta Description', 'required']) !!}
                            {!! $errors->first('meta_description', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
                        {!! Form::label('content', 'Content', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Content', 'required']) !!}
                            {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
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
<script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'content',{            
    removePlugins: 'elementspath,magicline',
    resize_enabled: false,
    allowedContent: true,
    enterMode: CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_BR,
    toolbar: [
        [ 'Source','-','Image','-','Bold','-','Italic','-','Underline'],
    ],
});
</script>
@endsection
