@extends('backend.includes.layout')

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#job-select').on('change', function(){
				$('#hoursForm').submit();
			});

			$('#date-select').on('change', function(){
				$('#hoursForm').submit();
			});
	/*		$('.editable').editable({
				params: function( params ) {
				    params.user;
				    return params;
				}
			});*/
		});
	</script>

	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

@stop

@section('content')
	
	<div class="add-user-form col-sm-12">

		{!! Form::open(['method' => 'POST', 'id' => 'hoursForm']) !!}
			<div class="col-sm-12" style="margin-bottom:10px;">
		    	<div class="col-sm-3 col-sm-offset-1">
					<h1 class="menu-h1">
						JOB NUMBER:
					</h1>
				</div>
				<div class="col-sm-8">
					<select name="job" id='job-select' class="fancy-select">
						@foreach($jobs as $id => $number)
							<option value="{{$id}}" @if($id == $job_id) selected="selected" @endif>
								{{$number}}
							</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="col-sm-12" style="margin-bottom:10px;">
		    	<div class="col-sm-3 col-sm-offset-1">
					<h1 class="menu-h1">
						WEEK START:
					</h1>
				</div>
				<div class="col-sm-8">
					<select name="date" id='date-select' class="fancy-select">
						@foreach($dates as $d)
							<option value="{{$d}}" @if($d == $fromDate) selected="selected" @endif>{{date('d/m/y', strtotime($d))}}</option>
						@endforeach	
					</select>
				</div>
			</div>
		{!! Form::close() !!}

		<div class="col-sm-12">
	    	<div class="col-sm-3 col-sm-offset-1">
				<h1 class="menu-h1">
					EXPORT PDF:
				</h1>
			</div>
			<div class="col-sm-8">
				{!! Form::open() !!}
					<button class="download-btn"></button>
				{!! Form::close() !!}
			</div>
		</div>

	</div>

	


	<div class="col-sm-8 col-sm-offset-2">
		<table id="datatables" class="table-responsive table-bordered table-hover" width="100%">
			<thead>
				<th>
					Operative
				</th>
				<th>
					Sat
				</th>
				<th>
					Sun
				</th>
				<th>
					Mon
				</th>
				<th>
					Tues
				</th>
				<th>
					Wed
				</th>
				<th>
					Thurs
				</th>
				<th>
					Fri
				</th>
				<th>
					Total
				</th>
			</thead>
			<tbody class="text-center">
				@foreach($logArray as $user => $weeklyTimes)

					<tr>
						<td>
							{{ $users[$user] }}
						</td>
						<?php $total = 0; ?>
						@foreach($weeklyTimes as $day => $time)
							<td>
								<a href="#" id="{{$user}}-{{$day}}" class="editable" data-type="text" data-pk="1" data-url="/ajaxedit" data-title="Enter username">
									@if(isset($time['time']) && $time['time'])
										{{$time['time']}}
										<?php $total += $time['time']; ?>
										
									@else
										-
									@endif
								</a>
								<script type="text/javascript">
									$(document).ready(function(){
										$("#{{$user}}-{{$day}}").editable({
											params: function( params ) {
											    params.user = '{{$user}}';
											    params.day = '{{$day}}';
											    params.job_id = '{{$job_id}}'
											    params.startDate = '{{$fromDate}}';
											    params.overtime = 0;

											    return params;
											}
										})
									});
								</script>
								
							</td>
						@endforeach

						<td>
							{{$total}}
						</td>

					</tr>

					<tr class="blueCells">
						<td>
							Overtime
						</td>
						<?php $total = 0; ?>
						@foreach($weeklyTimes as $day => $time)
							
							<td>
								<a href="#" id="over-{{$user}}-{{$day}}" class="editable" data-type="text" data-pk="1" data-url="/ajaxedit" data-title="Enter username">
									@if(isset($time['overtime']) && $time['overtime'])
										{{$time['overtime']}}
										<?php $total += $time['overtime']; ?>
									@else
										-
									@endif
								</a>
							</td>
							
							<script type="text/javascript">
								$(document).ready(function(){
									$("#over-{{$user}}-{{$day}}").editable({
										params: function( params ) {
										    params.user = '{{$user}}';
										    params.day = '{{$day}}';
										    params.job_id = '{{$job_id}}'
										    params.startDate = '{{$fromDate}}';
										    params.overtime = 1;

										    return params;
										}
									})
								});
							</script>
						@endforeach

						<td>
							{{$total}}
						</td>

					</tr>
				@endforeach
			</tbody>
		</table>
	</div>


@stop