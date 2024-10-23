
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Drop Shipper</header>
            <div class="panel-body">
                <div class="position-center">
                    
                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                        {!! Form::label('first_name', 'First Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => 'First Name','required' => 'required']) !!}
                            {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                        {!! Form::label('last_name', 'Last Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::text('last_name', null, ['class' => 'form-control','placeholder' => 'Last Name','required' => 'required']) !!}
                            {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                        {!! Form::label('email', 'Email', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            @if(@$user) 
                            {!! Form::email('email', null, ['class' => 'form-control','placeholder' => 'Email','readonly']) !!}
                            @else
                            {!! Form::email('email', null, ['class' => 'form-control','placeholder' => 'Email','required' => 'required']) !!}
                            @endif
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('company_name') ? 'has-error' : ''}}">
                        {!! Form::label('company_name', 'Company Name', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::text('company_name', null, ['class' => 'form-control','placeholder' => 'Company Name']) !!}
                            {!! $errors->first('company_name', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('vat_number') ? 'has-error' : ''}}">
                        {!! Form::label('vat_number', 'Vat #', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::text('vat_number', null, ['class' => 'form-control','placeholder' => 'Vat #']) !!}
                            {!! $errors->first('vat_number', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
                        {!! Form::label('phone', 'Contact #', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::text('phone', null, ['class' => 'form-control','placeholder' => 'Contact #']) !!}
                            {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>                   
                    <div class="form-group {{ $errors->has('max_limit') ? 'has-error' : ''}}">
                        {!! Form::label('max_limit', 'Max Limit', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::number('max_limit', null, ['class' => 'form-control','placeholder' => 'Max Limit','min' => '0']) !!}
                            {!! $errors->first('max_limit', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('percentage_1') ? 'has-error' : ''}}">
                        {!! Form::label('percentage_1', 'Cost Percentage', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::number('percentage_1', null, ['class' => 'form-control','placeholder' => 'Cost Percentage','min' => '0']) !!}
                            {!! $errors->first('percentage_1', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('is_active') ? 'has-error' : ''}}">
                        {!! Form::label('is_active', 'Status', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {!! Form::select('is_active', ['yes'=>'Active','no'=>'Inactive'],null, ['class' => 'form-control']) !!}
                            {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    @if(@!$user)                    
                    
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
                    @endif
                    
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

