@extends('admin.layouts.auth')

@section('content')
<div class="container">
<form class="form-signin cmxform" method="POST" action="{{ url('admin/login') }}" style="margin-top: 150px;">
      {{ csrf_field() }}
    <h2 class="form-signin-heading">sign in now</h2>
    <div class="login-wrap">
        <div class="user-login-info">
            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                <input type="email" name="email" class="form-control" placeholder="Email" value="" autofocus>
                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                <input type="password" name="password" class="form-control" placeholder="Password">
                {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
            </div>
            
        </div>
        <!--<label class="checkbox">
            <input type="checkbox" value="remember-me"> Remember me-->
            <!-- <span class="pull-right">
                <a href="{{ url('company/password/reset') }}"> Forgot Password?</a>
            </span>
        </label> -->
        <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>

    </div>
  </form>

</div>

@endsection
