

<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Slider</header>
            <div class="panel-body">
                <div class="position-center">                                                                            
                  
                  <div class="form-group hidden {{ $errors->has('html') ? 'has-error' : ''}}">
                    {!! Form::label('html', 'Slider HTML', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('html', null, ['class' => 'form-control','placeholder'=>'Slider HTML','required' => 'required']) !!}
                        {!! $errors->first('html', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>

                <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">                     
                    {!! Form::label('image', 'Slider Image', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}                                            
                    <div class="col-md-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px;">
                                @if(@$slider->image != '')
                                    <img src="{{ checkImage('sliders/'. $slider->image) }}" alt="" />
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="" />
                                @endif
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
                  
                  <div class="form-group {{ $errors->has('ordering') ? 'has-error' : ''}}">
                    {!! Form::label('ordering', 'Display Order', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::number('ordering', null, ['class' => 'form-control','placeholder'=>'Display Order','required' => 'required','min'=>0]) !!}
                        {!! $errors->first('ordering', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>

                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status', 'Status', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, ['class' => 'form-control select2','placeholder'=>'Status','required' => 'required']) !!}
                        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
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
CKEDITOR.replace( 'html',{            
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

