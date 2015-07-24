@extends('backend.includes.layout')

@section('title')
	Hours per Operative
@stop

@section('content')

	<div class="add-user-form col-sm-12">
    	<div class="col-sm-3 col-sm-offset-1">
    		<h1>
                EDIT OPERATIVE HOURS:
    		</h1>
    		<h1>
    			<a @if($page == 'users') href="/admin/payments" @else href="/admin/operatives" @endif>
    				< BACK
    			</a>
    		</h1>
    	</div>
    	
    	<div class="col-sm-7 login-form">

    		<div class="form-group">
            	<div class="col-sm-3">
	            	<label for="name">Operative:</label>
            	</div>
            	<div class="col-sm-9">
	                <input type="text" class="login-input" value="{{$user->name}}" disabled="true">	            		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-sm-3">
	            	<label for="date">Week Start:</label>
            	</div>
            	<div class="col-sm-9">
					@include('backend.includes.date')
				</div>
			</div>

			<div class="form-group">
            	<div class="col-sm-3">
	            	<label for="csv">
						EXPORT CSV:
						<div class="quote">
							*Export CSV for Operatives
						</div>
					</label>
				</div>
				<div class="col-sm-3">
					{!! Form::open() !!}
						<button class="download-btn"></button>
					{!! Form::close() !!}
				</div>
			</div>

    	</div>
    </div>

    <div class="add-user-form col-sm-12">
    	<div class="col-sm-7 col-sm-offset-4 login-form">

    		<div class="form-group">
    			<div class="col-sm-3">
	            	<label for="day">
	            		Day:
	            	</label>
	            </div>
	            <div class="col-sm-9">
	            	<select id='daySelect' class="fancy-select">
	            		<option value="0">Saturday</option>
	            		<option value="1">Sunday</option>
	            		<option value="2">Monday</option>
	            		<option value="3">Tuesday</option>
	            		<option value="4">Wednesday</option>
	            		<option value="5">Thursday</option>
	            		<option value="6">Friday</option>
	            	</select>
	            </div>
    		</div>

    	</div>
    </div>




@stop