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
		});
	</script>
@stop

@section('content')
	{!! Form::open(['method' => 'POST', 'id' => 'hoursForm']) !!}
		<div class="add-user-form col-sm-12">

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

			<div class="col-sm-12">
		    	<div class="col-sm-3 col-sm-offset-1">
					<h1 class="menu-h1">
						WEEK START:
					</h1>
				</div>
				<div class="col-sm-8">
					<select name="date" id='date-select' class="fancy-select">
						@foreach($dates as $d)
							<option value="{{$d}}" @if($d == @$fromDate) selected="selected" @endif>{{date('d/m/y', strtotime($d))}}</option>
						@endforeach	
					</select>
				</div>
			</div>
		</div>

	{!! Form::close() !!}



	<table>
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
		<tbody>
			@foreach($logArray as $user => $weeklyTimes)

				<tr>
					<td>
						{{$user}}
					</td>
					<?php $total = 0; ?>
					@foreach($weeklyTimes as $day => $time)
						<td>
							@if(isset($time['time']))
								{{$time['time']}}
								<?php $total += $time['time']; ?>
							@else
								-
							@endif
						</td>
					@endforeach

					<td>
						{{$total}}
					</td>

				</tr>

				<tr>
					<td>
						{{$user}}
					</td>
					<?php $total = 0; ?>
					@foreach($weeklyTimes as $day => $time)
						<td>
							@if(isset($time['overtime']) && $time['overtime'])
								{{$time['overtime']}}
								<?php $total += $time['overtime']; ?>
							@else
								-
							@endif
						</td>
					@endforeach

					<td>
						{{$total}}
					</td>

				</tr>
			@endforeach
		</tbody>
	</table>


@stop