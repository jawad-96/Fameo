@extends('admin.layouts.app')

@section('content')

<section id="main-content">
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            
            {!! Form::model($role, ['method' => 'PUT', 'url' => ['admin/roles/permissions',  Hashids::encode($role->id) ]]) !!}
            
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel"><header class="panel-heading">{{ $role->name }} Permissions</header>
                        <div class="panel-body toggle-heading">
                            
                            @foreach($permissions as $permission)                                                     
                                <div class="col-sm-3">     
                                    <h3>{{ ucwords($permission->name) }}</h3>
                                    <div class="m-bot20">
                                        {!! Form::checkbox("permissions[]", $permission->name, $role->hasPermissionTo($permission->name), ["data-on" => "success", "data-off" => "danger"]) !!} 
                                    </div>
                                </div>
                            @endforeach

                            @can('edit role permission')
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    {!! Form::submit('Save', ['class' => 'btn btn-info pull-right']) !!}
                                </div>
                            </div>
                            @endcan
                        </div>
                    </section>
                </div>   
            </div>
            {!! Form::close() !!}    
                
                
            </div>
        </div>
</section>
</section>
@endsection

@section('scripts')
    <script src="{{ asset('js/toggle-init.js') }}"></script>
@endsection
    





