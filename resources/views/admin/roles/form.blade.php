
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Role</header>
            <div class="panel-body">
                <div class="position-center">                    
                    
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Role Name', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                        <div class="col-lg-9">
                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Role Name','required' => 'required']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
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