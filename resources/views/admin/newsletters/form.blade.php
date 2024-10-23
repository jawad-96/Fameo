<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Newsletter</header>
            <div class="panel-body">
                <div class="position-center">                    

                    <div class="form-group {{ $errors->has('from') ? 'has-error' : ''}}">
                        {!! Form::label('from', 'From', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::email('from', null, ['class' => 'form-control', 'placeholder' => 'From', 'required']) !!}
                            {!! $errors->first('from', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
                        {!! Form::label('subject', 'Subject', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('subject', null, ['class' => 'form-control', 'placeholder' => 'Subject', 'required']) !!}
                            {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
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
                            <!-- {!! Form::button('Save & Send', ['class' => 'btn btn-info send_newsletter']) !!} -->
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
<script type="text/javascript">
    $(document).ready(function(){   
    $('.invisible').val(0);           
      $(document).on("click",".send_newsletter", function(){
        $('.invisible').val(1);
        $('#formId').submit();
      });
    });//end of ready()
</script>
@endsection

