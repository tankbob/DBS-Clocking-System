@extends('backend.includes.layout')

@section('title')
	Add Operative to work sheet
@stop

@section('content')
	@include('frontend.includes.errors')

    <div class="add-user-form col-sm-12">
    	<div class="col-sm-5">
    		<h1>MANUAL OPERATIVE:</h1>

    		<h1>
    			<a href='/admin/hours/?job={{$job_id}}&amp;date={{$fromDate}}' id="backSubmit">&lt; BACK</a>
    		</h1>
    	</div>

    	<div class="col-sm-7 login-form">
    		{!! Form::open(['url' => 'admin/addoperative', 'method' => 'post', 'class' => 'form-horizontal']) !!}
    			<div class="form-group">
	            	<div class="col-sm-3 no-side-padding">
		            	<label for="user_id">Name:</label>
	            	</div>
	            	<div class="col-xs-9">
	            		{!! Form::select('user_id', $users, '', ['class' => 'fancy-select big-fancy-select']) !!}
	            	</div>
	            </div>
	            <div class="form-group">
	            	<div class="col-sm-3 no-side-padding">
		            	<label for="job">Job:</label>
	            	</div>
	            	<div class="col-xs-9">
	            		{!! Form::text('job', $job->id, ['class' => 'login-input hidden']) !!}
	            		<p>{{$job->number}}</p>
	            	</div>
	            </div>
	            <div class="form-group">
	            	<div class="col-sm-3 no-side-padding">
		            	<label for="date">Week Start:</label>
	            	</div>
	            	<div class="col-xs-9">
	            		{!! Form::text('date', $fromDate, ['class' => 'login-input hidden']) !!}
	            		<p>{{date('d/m/y', strtotime($fromDate))}}</p>
	            	</div>
	            </div>

	            <div class="col-sm-9 col-sm-offset-3">
	            <button class="update-btn"></button>
	            </div>
    		{!!Form::close()!!}
    	</div>
    </div>
@stop