@extends('backend.includes.layout')

@section('title')
	Hours per Operative
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#daySelect').on('change', function(){
				$('.timesDiv').addClass('hidden');
				$('#times-'+$('#daySelect').val()).removeClass('hidden');
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#date-select').on('change', function(){
				$('#viewdate').submit();
			});
		});
</script>
@stop

@section('content')

	@include('backend.includes.errors')

	<div class="add-user-form col-sm-12">
    	<div class="col-sm-3 col-sm-offset-1">
    		<h1>
                EDIT OPERATIVE HOURS:
    		</h1>
    		<h1>
    			<a href="/admin/payment">
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
					{!! Form::open(['method' => 'GET', 'id' => 'viewdate']) !!}

						<select name="date" id='date-select' class="fancy-select">
							@foreach($dates as $d)
								<option value="{{$d}}" @if($d == @$fromDate) selected="selected" @endif>{{date('d/m/y', strtotime($d))}}</option>
							@endforeach	
						</select>

					{!! Form::close() !!}
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

    		@foreach($times as $i => $t)
    			<div class="timesDiv @if($i > 0) hidden @endif " id="times-{{$i}}">
		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Hours:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['Mon-Fri']}}
				  			</p>          	
			            </div>
			        </div>

		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Holiday:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['Holiday']}}
				  			</p>          	
			            </div>
			        </div>

		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Overtime:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['overtime']}}
				  			</p>          	
			            </div>
			        </div>

		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Weekends:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['Weekends']}}
				  			</p>          	
			            </div>
			        </div>
			    </div>
		    @endforeach
    	</div>
    </div>

    <div class="add-user-form col-sm-12">
    	<div class="col-sm-7 col-sm-offset-4 login-form">
	    	{!! Form::open(['url' => '/admin/missedhours', 'method' => 'POST']) !!}

	    		<input class="hidden" name="user_id" value="{{$user->id}}">
	    		<input class="hidden" name="fromDate" value="{{$fromDate}}">

	    		<div class="form-group">
	    			<div class="col-sm-3">
		            	<label for="time">
		            		Hours:
		            	</label>
		            </div>
		            <div class="col-sm-9">
		            	{!! Form::text('time', @$missed->time + @$missed->overtime) !!}
		            </div>
	    		</div>

	    		<div class="form-group">
	    			<div class="col-sm-3">
		            	<label for="hour_type_id">
		            		Pay Type:
		            	</label>
		            </div>
		            <div class="col-sm-9">
		            	<select name="hour_type_id" class="fancy-select">
		            		<option></option>
		            		@foreach($hourTypes as $key => $val)
		            			<option value="{{$key}}" @if($key == @$missed->hour_type_id) selected="selected" @endif>{{$val}}</option>
		            		@endforeach
		            		<option value="overtime"@if(@$missed->overtime) selected="selected" @endif>Overtime</option>
		            	</select> 
		            </div>
	    		</div>

	    		<div class="text-center">
	    			<button class="update-btn"></button>
	    		</div>

	    	{!! Form::close() !!}
    	</div>

    </div>

     <div class="add-user-form col-sm-12">
            <div class="col-sm-3 col-sm-offset-1">
                <h1>
                    OPERATIVE PAY RATE:
                </h1>
                <span class="advice-span">*Reference Only</span>
            </div>
            
            <div class="col-sm-7 login-form">

                <div class="form-group">
                    <div class="col-xs-3">
                        <label for="standard_salary">Standard &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('standard_salary', $user->standard_salary, ['class' => 'login-input', 'disabled' => true]) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-3">
                        <label for="weekends_5_salary">Weekends 5 &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('weekends_5_salary', $user->weekends_5_salary, ['class' => 'login-input', 'disabled' => true]) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-3">
                        <label for="weekends_9_salary">Weekends 9 &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('weekends_9_salary', $user->weekends_9_salary, ['class' => 'login-input', 'disabled' => true]) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-3">
                        <label for="holiday_salary">Holiday &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('holiday_salary', $user->holiday_salary, ['class' => 'login-input', 'disabled' => true]) !!}                
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-3">
                        <label for="overtime_salary">Overtime &pound;:</label>
                    </div>
                    <div class="col-xs-9">
                       {!! Form::text('overtime_salary', $user->overtime_salary, ['class' => 'login-input', 'disabled' => true]) !!}                
                    </div>
                </div>
            </div>
        </div>



@stop