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

	@include('backend.includes.errors')

    @if($page == 'users')
        {!! Form::open(['url' => 'admin/users/' . $user->id, 'method' => 'put', 'class' => 'form-horizontal']) !!}
    @else
        {!! Form::open(['url' => 'admin/operatives/' . $user->id, 'method' => 'put', 'class' => 'form-horizontal']) !!}
    @endif

    <input class="hidden" value="{{$user->id}}" name="id">

    <div class="add-user-form col-xs-12">
    	<div class="col-sm-5">
    		<h1>
                @if($page == 'users')
                    EDIT USER:
                @else
                    EDIT OPERATIVE:
                @endif
    		</h1>

    		<h1>
    			<a @if($page == 'users') href="/admin/users" @else href="/admin/operatives" @endif>
    				< BACK
    			</a>
    		</h1>
    	</div>
    	
    	<div class="col-sm-7 login-form">
    
            <div class="form-group">
            	<div class="col-sm-3 no-side-padding">
	            	<label for="name">Name:</label>
            	</div>
            	<div class="col-xs-9">
            		{!! Form::text('name', $user->name, ['class' => 'login-input']) !!}
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-sm-3 no-side-padding">
	            	<label for="email">Email:</label>
            	</div>
            	<div class="col-xs-9">
	               {!! Form::text('email', $user->email, ['class' => 'login-input']) !!}           		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-sm-3 no-side-padding">
	            	<label for="password">Password:</label>
            	</div>
            	<div class="col-xs-9">
	                <input type="password" name="password" id="password" class="login-input">
	                <div class="advice-span">Leave password field blank to keep the same password</div>
            	</div>
            </div>

            @if($page == 'operatives')
                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="telephone">Telephone:</label>
                    </div>
                    <div class="col-xs-9">
                         {!! Form::text('telephone', $user->telephone, ['class' => 'login-input']) !!}                         
                    </div>
                </div>
            @endif

            <div class="col-sm-9 col-sm-offset-3">
                <button class="update-btn">

                </button>
            </div>
        </div>
    </div> 

    @if($page == 'operatives')
        <div class="col-sm-12">
            <div class="col-sm-5">
                <h1>
                    OPERATIVE PAY RATE:
                </h1>
                <div class="advice-span">*Reference Only</div>
            </div>
            
            <div class="col-sm-7 login-form">

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="standard_salary">Standard &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('standard_salary', $user->standard_salary, ['class' => 'login-input']) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="weekends_5_salary">Weekends 5 &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('weekends_5_salary', $user->weekends_5_salary, ['class' => 'login-input']) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="weekends_9_salary">Weekends 9 &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('weekends_9_salary', $user->weekends_9_salary, ['class' => 'login-input']) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="holiday_salary">Holiday &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('holiday_salary', $user->holiday_salary, ['class' => 'login-input']) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="overtime_salary">Overtime &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('overtime_salary', $user->overtime_salary, ['class' => 'login-input']) !!}                
                    </div>
                </div>
            </div>
        </div>
    @endif


    {!! Form::close() !!}
@stop