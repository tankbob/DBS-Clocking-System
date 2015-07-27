@extends('frontend.includes.layout')

@section('title')
	Operative Information
@stop

@section('content')
	<div class="op-name text-center">
		{{Auth::user()->name}}
	</div>

	@include('frontend.includes.dateDiv')

	@include('frontend.includes.errors')

	@if(!isset($date))

	{!! Form::open(['url' => '/edittimes', 'method' => 'POST']) !!}

	@endif

		@foreach($logTimes as $count => $logTime)

			<div class="log-time-div @if(count($logTimes) > $count +1) border-bottom @endif">
				<div class="form-group text-center">

					{!! Form::label($logTime->id.'[job_number]', 'Job:') !!}

					{!! Form::text($logTime->id.'[job_number]', $logTime->Job->number, ['disabled' => 'true']) !!}

				</div>
				
				<div class="form-group text-center">

					{!! Form::label($logTime->id.'[hour_type_id]', 'Hour Type:') !!}

					{!! Form::text($logTime->id.'[hour_type_id]', $logTime->HourType->value, ['disabled' => 'true']) !!}

				</div>

				<div class="form-group text-center">
					{!! Form::label($logTime->id.'[time]', 'Hours:') !!}
					<select name="{{$logTime->id}}[time]" @if(isset($date)) disabled="true" @endif>
						@for($i = 0; $i <= 9; $i++)
							<option value="{{$i}}" @if($logTime->time == $i) selected="selected" @endif>
								{{$i}} hrs
							</option>
						@endfor
					</select>
				</div>

				<div class="form-group text-center">
					{!! Form::label($logTime->id.'[overtime]', 'Overtime:') !!}
					<select name="{{$logTime->id}}[overtime]" @if(isset($date)) disabled="true" @endif>
						<option value="0"> Add O.T. </option>
						@for($i = 1; $i <= 9; $i++)
							<option value="{{$i}}" @if($logTime->overtime == $i) selected="selected" @endif>
								{{$i}} hrs
							</option>
						@endfor
					</select>
				</div>
			</div>

		@endforeach

		@if(!isset($date))
			

			<div class="text-center">
	            <button class="update-btn">
					
	            </button>
	        </div>

	        <div class="text-center">
	            <a class="addjob-btn" href="/addjob">
	              
	            </a>
	        </div>
	    

	{!! Form::close() !!}
	@endif
@stop