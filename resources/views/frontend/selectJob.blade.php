@extends('frontend.includes.layout')

@section('title') Sign In @stop

@section('content')
	<div class="op-name text-center">
		{{ Auth::user()->name }}
	</div>
	@include('frontend.includes.errors')

	<div class="col-md-4 col-md-offset-4">
	{!! Form::open(['url' => 'signin', 'method' => 'post', 'class' => 'form-horizontal']) !!}
		<div class="form-group select-job-number offset-form-input">
			{!! Form::label('job_id', 'Job Number:', ['class' => 'col-xs-6 control-label text-left']) !!}
			<div class="col-xs-6">
				{!! Form::select('job_id', [null => 'Select Job'] + $jobs->all(), null, ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group select-hour-type offset-form-input">
			{!! Form::label('hour_type_id', 'Hour Type:', ['class' => 'col-xs-6 control-label text-left']) !!}
			<div class="col-xs-6">
				{!! Form::select('hour_type_id', [null => 'Hour Type'] + $hourTypes->all(), null, ['class' => 'form-control']) !!}
			</div>
		</div>

		<div class="text-center">
			<button class="signin-btn"></button>
		</div>
	{!! Form::close() !!}
	</div>
@stop