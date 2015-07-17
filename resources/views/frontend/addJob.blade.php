@extends('frontend.includes.layout')

@section('title')
	Additional Job
@stop

@section('scripts')

@stop

@section('content')

	<div class="op-name text-center">
		{{Auth::user()->name}}
	</div>

	@include('frontend.includes.errors')

	{!! Form::open(['url' => '/addjob', 'method' => 'POST']) !!}

		<div class="form-group text-center">

			{!! Form::label('job_id', 'Job Number:') !!}

			{!! Form::select('job_id', [null => 'Select Job'] + $jobs->all()) !!}

		</div>

		<div class="text-center">
            <button class="update-btn">
              
            </button>
        </div>

	{!! Form::close() !!}

@stop