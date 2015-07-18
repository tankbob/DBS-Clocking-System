@extends('backend.includes.layout')

@section('title')
	@if($page = 'users')
		Users
	@else
		Operatives
	@endif
@stop

@section('content')

	@include('frontend.includes.errors')

	<div class="col-sm-4">
		<h1>
			ADD USER:
		</h1>
	</div>
	
	<div class="col-sm-8 login-form">
        
        {!! Form::open(['url' => '/admin/users', 'method' => 'POST']) !!}

            <div class="form-group">
            	<div class="col-xs-3">
	            	<label for="name">Name:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="name" name="name" value="{{ old('name') }}" class="login-input">	            		
            	</div>
            </div>

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

            <input class="hidden" name="loginType" value="Admin">

            <div class="text-center">
                <button class="submit-btn">

                </button>
            </div>
           
        {!! Form::close() !!}
    </div>
@stop