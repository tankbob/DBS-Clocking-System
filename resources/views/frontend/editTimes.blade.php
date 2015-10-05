@extends('frontend.includes.layout')

@section('title') Operative Information @stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#date-select').on('change', function(){
			$('#viewdate').submit();
		});

		$('.hour-type-select').on('change', function(){
			if($(this).find("option:selected").text() == 'Normal'){
				$('#number-normal-'+$(this).attr('id')).prop('disabled', false);
				$('#number-normal-'+$(this).attr('id')).removeClass('disabled-select hidden');
				$('#number-holiday-'+$(this).attr('id')).prop('disabled', true);
				$('#number-holiday-'+$(this).attr('id')).addClass('disabled-select hidden');
			}else{
				$('#number-normal-'+$(this).attr('id')).prop('disabled', true);
				$('#number-normal-'+$(this).attr('id')).addClass('disabled-select hidden');
				$('#number-holiday-'+$(this).attr('id')).prop('disabled', false);
				$('#number-holiday-'+$(this).attr('id')).removeClass('disabled-select hidden');
			}
		});
	});
</script>
@stop

@section('content')
	<div class="op-name text-center">{{Auth::user()->name}}</div>

	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			@include('frontend.includes.dateDiv')
		</div>
	</div>
	
	<hr>

	@include('frontend.includes.errors')

	<div class="col-md-4 col-md-offset-4 form-horizontal">
		@if(!isset($date))
			{!! Form::open(['url' => 'edittimes', 'method' => 'post']) !!}
		@endif
		@foreach($logTimes as $count => $logTime)
			<div class="log-time-div @if(count($logTimes) > $count +1) border-bottom @endif">
				<div class="form-group text-center offset-form-input">
					{!! Form::label($logTime->id.'[job_number]', 'Job:', ['class' => 'col-xs-6 control-label']) !!}
					<div class="col-xs-6">
						{!! Form::text($logTime->id.'[job_number]', $logTime->Job->number, ['disabled' => 'true']) !!}
					</div>
				</div>
			
				<div class="form-group text-center offset-form-input">
					{!! Form::label($logTime->id.'[hour_type_id]', 'Hour Type:', ['class' => 'col-xs-6 control-label']) !!}
					<div class="col-xs-6">
						<select name="{{$logTime->id}}[hour_type_id]" id="hour-select-{{$logTime->id}}" @if(isset($date)) disabled="true" class="hour-type-select disabled-select" @else class="hour-type-select" @endif>
							@foreach($hourTypes as $ht_id => $ht_val)
								<option value="{{$ht_id}}" @if($logTime->hour_type_id == $ht_id) selected="selected" @endif>{{ $ht_val }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group text-center offset-form-input">
					{!! Form::label($logTime->id.'[time]', 'Hours:', ['class' => 'col-xs-6 control-label']) !!}
					<div class="col-xs-6">
						<select name="{{$logTime->id}}[time]" id="number-normal-hour-select-{{$logTime->id}}" @if(isset($date)) disabled="true" class="disabled-select" @elseif($logTime->hourType->value == 'Holiday') disabled="true" class="disabled-select hidden" @endif>
							@if(isset($date) || date('N') <= 5)
							<!-- Editor and it's week day -->
								@for($i = 0; $i <= 9; $i++)
									<option value="{{ $i }}" 
									@if($logTime->time == $i) selected="selected" @endif
									>{{ $i }} hrs</option>
								@endfor
								<option value="13.5" @if($logTime->time == 13.5) selected="selected" @endif >13.5 hrs</option>
								<option value="18" @if($logTime->time == 18) selected="selected" @endif >18 hrs</option>
							@else
								<!-- It's weekend -->
								@foreach([0, 5, 9] as $i)
									<option value="{{ $i }}" 
									@if($logTime->time == $i) selected="selected" @endif 
									>{{ $i }} hrs</option>
								@endforeach
							@endif
						</select>
						<select name="{{$logTime->id}}[time]" id="number-holiday-hour-select-{{$logTime->id}}" @if(isset($date)) disabled="true" class="disabled-select" @elseif($logTime->hourType->value == 'Normal') disabled="true" class="disabled-select hidden" @endif>
							<option value="0" 
								@if($logTime->time == 0) selected="selected" @endif
							>0 hrs</option>
							<option value="9" 
								@if($logTime->time == 9) selected="selected" @endif
							>9 hrs</option>
						</select>
					</div>
				</div>

				<div class="form-group text-center offset-form-input">
					{!! Form::label($logTime->id.'[overtime]', 'Overtime:', ['class' => 'col-xs-6 control-label']) !!}
					<div class="col-xs-6">
						<select name="{{$logTime->id}}[overtime]" @if(isset($date)) disabled="true" class="disabled-select" @endif>
							<option value="0"> Add O.T. </option>
							@for($i = 1; $i <= 9; $i++)
								<option value="{{ $i }}" @if($logTime->overtime == $i) selected="selected" @endif>{{ $i }} hrs</option>
							@endfor
						</select>
					</div>
				</div>
			</div>
		@endforeach

		@if(!isset($date))
			<div class="text-center">
				<button class="update-btn"></button>
			</div>
			<div class="text-center">
				<a class="addjob-btn" href="/addjob"></a>
			</div>
			{!! Form::close() !!}
		@endif
	</div>
@stop