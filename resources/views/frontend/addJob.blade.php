@extends('frontend.includes.layout')

@section('title') Additional Job @stop

@section('content')
	<div class="op-name text-center">
		{{Auth::user()->name}}
	</div>

	<div class="col-md-4 col-md-offset-4">
		@include('frontend.includes.errors')

		<div class="text-center">
			<a class="goback-btn" href="/"></a>
		</div>

		{!! Form::open(['url' => '/addjob', 'method' => 'post', 'class' => 'form-horizontal']) !!}
			<div class="form-group text-center offset-form-input add-job-form">
				{!! Form::label('job_id', 'Job Number:', ['class' => 'col-xs-6 control-label']) !!}
				<div class="col-xs-6">
					{!! Form::select('job_id', [null => 'Select Job'] + $jobs->all()) !!}
				</div>
			</div>
			<div class="text-center">
				<button class="update-btn"></button>
			</div>
		{!! Form::close() !!}
	</div>
@stop