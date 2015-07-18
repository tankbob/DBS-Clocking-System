@extends('backend.includes.layout')

@section('title')
	@if($page == 'users')
		Users
	@else
		Operatives
	@endif
@stop

@section('scripts')
       
@stop

@section('content')

	@include('frontend.includes.errors')

    <div class="add-user-form col-sm-12">
    	<div class="col-sm-3 col-sm-offset-1">
    		<h1>
    			EDIT USER:
    		</h1>

    		<h1>
    			<a @if($page == 'users') href="/admin/users" @else href="/admin/operative" @endif>
    				< BACK
    			</a>
    		</h1>
    	</div>
    	
    	<div class="col-sm-8 login-form">
            
            {!! Form::open(['url' => "/admin/users/$user->id", 'method' => 'PUT']) !!}

                <div class="form-group">
                	<div class="col-xs-3">
    	            	<label for="name">Name:</label>
                	</div>
                	<div class="col-xs-9">
                		{!! Form::text('name', $user->name, ['class' => 'login-input']) !!}
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-3">
    	            	<label for="email">Email:</label>
                	</div>
                	<div class="col-xs-9">
    	               {!! Form::text('email', $user->email, ['class' => 'login-input']) !!}           		
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-3">
    	            	<label for="password">Password:</label>
                	</div>
                	<div class="col-xs-9">
    	                <input type="password" name="password" id="password" class="login-input">
    	                <span class="advice-span">Leave password field blank to keep the same password</span>
                	</div>
                </div>

                <input class="hidden" name="loginType" value="Admin">

                <div class="text-center">
                    <button class="update-btn">

                    </button>
                </div>
               
            {!! Form::close() !!}
        </div>
    </div>
@stop