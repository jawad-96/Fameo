
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Basic Information</header>
            <div class="panel-body">
                <div class="position-center">
                    
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Name','required' => 'required']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                        {!! Form::label('role', 'Role', ['class' => 'col-md-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9 col-md-9 col-sm-9">
                            {{ Form::select('role',  $roles, null,['class' => 'form-control select2']) }}
                            {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
                        </div>
                      </div>  

                @if(@$admin ?? '' ?? '')
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-info pull-right']) !!}
                    </div>
                </div>
                @endif
                </div>
            </div>
        </section>

    </div>
    
    @if(@!$admin ?? '' ?? '')
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Login Information</header>
            <div class="panel-body">
                <div class="position-center">
                    
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                        {!! Form::label('email', 'Email', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::email('email', null, ['class' => 'form-control','placeholder' => 'Email','required' => 'required']) !!}
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                        {!! Form::label('password', 'Password', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Password','required' => 'required','data-minlength' => 6]) !!}
                            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    
                    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::password('password_confirmation', ['class' => 'form-control','placeholder' => 'Confirm Password','required' => 'required','data-match'=>'#password']) !!}
                            {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
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
    @endif
</div>


@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
                
    });
</script>
@endsection