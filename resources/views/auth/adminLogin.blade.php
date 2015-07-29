@extends('backend.includes.layout')

@section('title')
	Login
@stop

@section('content')

	@include('backend.includes.errors')

	<div class="col-sm-3 col-sm-offset-1">
		<h1>
			LOGIN:
		</h1>
	</div>
	
	<div class="col-sm-7 login-form">
        
        <form method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="email">Email:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="email" name="email" value="{{ old('email') }}" class="login-input">	            		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="password">Password:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="password" name="password" id="password" class="login-input">
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="g-recaptcha-response">Captcha:</label>
            	</div>
            	<div class="col-xs-9">
           			{!! Recaptcha::render() !!}
           		</div>
           	</div>

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="remember">Remember Me:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="checkbox" name="remember" id="remember">
            	</div>
            </div>

            <input class="hidden" name="loginType" value="Admin">

            <div class="text-center">
                <button class="login-btn">

                </button>
            </div>
           
        </form>
    </div>

@stop