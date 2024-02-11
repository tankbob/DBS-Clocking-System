@extends('backend.includes.layout')

@section('title')
	Clocked Hours
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#job-select').on('change', function(){
				$('#hoursForm').submit();
			});
			$('#date-select').on('change', function(){
				$('#hoursForm').submit();
			});
		});
	</script>

	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

@stop

@section('content')
	@include('backend.includes.errors')
	<div class="add-user-form col-sm-12">
		{!! Form::open(['url' => '/admin/hours', 'method' => 'GET', 'id' => 'hoursForm', 'class' => 'form-horizontal']) !!}
        <div class="col-sm-12" style="margin-bottom:10px;">
            <div class="col-sm-3 no-side-padding">
                <h1 class="menu-h1">WEEK START:</h1>
            </div>
            <div class="col-sm-9">
                <select name="date" id='date-select' class="fancy-select">
                    @foreach($dates as $d)
                    <option value="{{$d}}" @if($d == $fromDate) selected="selected" @endif>{{date('d/m/y', strtotime($d))}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        		<div class="col-sm-12" style="margin-bottom:10px;">
			    <div class="col-sm-3 no-side-padding">
					<h1 class="menu-h1">JOB NUMBER:</h1>
				</div>
				<div class="col-sm-9">
                    @if (count($jobs) > 0)
					<select name="job" id="job-select" class="fancy-select">
						@foreach($jobs as $id => $number)
							<option value="{{$id}}" @if($id == $job_id) selected="selected" @endif>{{$number}}</option>
						@endforeach
					</select>
                    @else
                       <h1 class="menu-h1>"No Jobs Found</h1>
                    @endif
				</div>
			</div>

		{!! Form::close() !!}
        @if (count($jobs) >0 )
		<div class="col-sm-12">
	    	<div class="col-sm-3 no-side-padding">
				<h1 class="menu-h1">EXPORT PDF:</h1>
			</div>
			<div class="col-sm-9">
				{!! Form::open(['url' => '/admin/hours/pdf', 'method' => 'POST', 'target' => '#blank']) !!}
					<input name="job" value="{{$job_id}}" class="hidden">
					<input name="date" value="{{$fromDate}}" class="hidden">
					<button class="download-btn"></button>
				{!! Form::close() !!}
			</div>
		</div>
        @endif
	</div>

	<div class="col-xs-12 add-user-form">
		<table id="datatables" class="hoursTable table-responsive table-bordered" width="96%">
			<thead>
				<th>Operative</th>
				<th>Sat</th>
				<th>Sun</th>
				<th>Mon</th>
				<th>Tues</th>
				<th>Wed</th>
				<th>Thurs</th>
				<th>Fri</th>
				<th>Total</th>
			</thead>
			<tbody class="text-center">
				@foreach($logArray as $user => $weeklyTimes)
					<tr>
						<td>{{ $users[$user] }}</td>
						<?php $total = 0; ?>
						@foreach($weeklyTimes as $day => $time)
							<td>
								<a href="#" id="{{$user}}-{{$day}}" class="editable" data-type="select" data-pk="1" data-url="/ajaxedittime" data-title="Enter value">
									@if(isset($time['time']) && $time['time'])
										{{ floatval($time['time']) }}
										<?php $total += $time['time']; ?>
										
									@else
										-
									@endif
								</a>
								<script type="text/javascript">
									$(document).ready(function(){
										$("#{{$user}}-{{$day}}").editable({
											value: @if(isset($time['time'])) value="{{floatval($time['time'])}}" @else value="0" @endif,
											params: function( params ) {
											    params.user = "{{$user}}";
											    params.day = "{{$day}}";
											    params.job_id = "{{$job_id}}";
											    params.startDate = "{{$fromDate}}";

											    return params;
											},
											source: [
												{0: 0},
												@if($day >= 2)
												{1: 1},
												{2: 2},
												{3: 3},
												{4: 4},
												@endif
												{5: 5},
												@if($day >= 2)
												{6: 6},
												{7: 7},
												{8: 8},
												@endif
												{9: 9},
												{13.5: 13.5},
												{18: 18}		
											],
											success: function(response, newValue) {
											    if(response.success){
											    	$("#total-"+{{$user}}).text(response.totalTime);
											    	$("#holiday-total-"+{{$user}}).text(response.totalHoliday);
											    	$("#holiday-"+{{$user}}+"-"+{{$day}}).text("-");
											    }
											}
										})
									});
								</script>
								
							</td>
						@endforeach

						<td>
							<div id="total-{{$user}}">{{$total}}</div>
						</td>

					</tr>

					<tr class="blackCells">
						<td>Overtime</td>
						<?php $total = 0; ?>
						@foreach($weeklyTimes as $day => $time)
							<td>
								<a href="#" id="over-{{$user}}-{{$day}}" class="editable" data-type="select" data-pk="1" data-url="/ajaxeditovertime" data-title="Enter value">
									@if(isset($time['overtime']) && $time['overtime'])
										{{floatval($time['overtime'])}}
										<?php $total += $time['overtime']; ?>
									@else
										-
									@endif
								</a>
							</td>
							
							<script type="text/javascript">
								$(document).ready(function(){
									$("#over-{{$user}}-{{$day}}").editable({
										value: @if(isset($time['overtime'])) value="{{floatval($time['overtime'])}}" @else value="0" @endif,
										params: function( params ) {
										    params.user = '{{$user}}';
										    params.day = '{{$day}}';
										    params.job_id = '{{$job_id}}'
										    params.startDate = '{{$fromDate}}';
										    return params;
										},
										source: [
											{0: 0},
											{1: 1},
											{2: 2},
											{3: 3},
											{4: 4},
											{5: 5},
											{6: 6},
											{7: 7},
											{8: 8},
											{9: 9}
										],
										success: function(response, newValue) {
										    if(response.success){
										    	$("#over-total-"+{{$user}}).text(response.total);
										    }
										}
									})
								});
							</script>
						@endforeach

						<td>
							<div id="over-total-{{$user}}">
								{{$total}}
							</div>
						</td>
					</tr>

					<tr class="blueCells">
						<td>
							Holiday
						</td>
						<?php $total = 0; ?>
						@foreach($weeklyTimes as $day => $time)
							<td>
								<a href="#" id="holiday-{{$user}}-{{$day}}" class="editable" data-type="select" data-pk="1" data-url="/ajaxeditholiday" data-title="Enter value">
									@if(isset($time['holiday']) && $time['holiday'])
										{{floatval($time['holiday'])}}
										<?php $total += $time['holiday']; ?>
									@else
										-
									@endif
								</a>
							</td>
							
							<script type="text/javascript">
								$(document).ready(function(){
									$("#holiday-{{$user}}-{{$day}}").editable({
										value: @if(isset($time['holiday'])) value="{{floatval($time['holiday'])}}" @else value="0" @endif,
										params: function( params ) {
										    params.user = '{{$user}}';
										    params.day = '{{$day}}';
										    params.job_id = '{{$job_id}}'
										    params.startDate = '{{$fromDate}}';
										    return params;
										},
										source: [
											{0: 0},
											{9: 9}
										],
										success: function(response, newValue) {
										    if(response.success){
										    	$("#total-"+{{$user}}).text(response.totalTime);
											    $("#holiday-total-"+{{$user}}).text(response.totalHoliday);
											    $("#"+{{$user}}+"-"+{{$day}}).text("-");
										    }
										}
									})
								});
							</script>
						@endforeach
						<td>
							<div id="holiday-total-{{$user}}">{{$total}}</div>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="col-xs-12" style="margin-top:30px;">
		<div class="col-xs-6">
			{!!Form::open(['url' => '/admin/addoperative', 'method' => 'GET'])!!}
				<input class="hidden" name="job_id" value="{{$job_id}}">
				<input class="hidden" name="fromDate" value="{{$fromDate}}">				
				<button class="addOperativeBtn"></button>
			{!!Form::close()!!}
		</div>
		<div class="col-xs-6">
			@if($showApproved)
				{!!Form::open(['url' => '/admin/hours/approve', 'method' => 'POST'])!!}
					<input class="hidden" name="job" value="{{$job_id}}">
					<input class="hidden" name="date" value="{{$fromDate}}">
					<button class="approveBtn"></button>
				{!!Form::close()!!}	
			@endif
			@if($showUnapproved)
				{!!Form::open(['url' => '/admin/hours/unapprove', 'method' => 'POST'])!!}
					<input class="hidden" name="job" value="{{$job_id}}">
					<input class="hidden" name="date" value="{{$fromDate}}">
					<button class="unapproveBtn"></button>
				{!!Form::close()!!}	
			@endif
		</div>
	</div>
@stop