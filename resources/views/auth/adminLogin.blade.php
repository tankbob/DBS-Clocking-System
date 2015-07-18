@extends('backend.includes.layout')

@section('title')
	Login
@stop

@section('content')

	@include('frontend.includes.errors')

	<div class="col-sm-4">
		<h1>
			LOGIN:
		</h1>
	</div>
	
	<div class="col-sm-8 login-form">
        
        <form method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="email">Email:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="email" name="email" value="{{ old('email') }}" class="login-input" placeholder="Email">	            		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="password">Password:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="password" name="password" id="password" class="login-input" placeholder="Password">
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