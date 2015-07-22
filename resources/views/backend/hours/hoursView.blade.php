@extends('backend.includes.layout')

@section('content')
	

	<table>
		<thead>
			<tr>
				Operative
			</tr>
			<tr>
				Sat
			</tr>
			<tr>
				Sun
			</tr>
			<tr>
				Mon
			</tr>
			<tr>
				Tues
			</tr>
			<tr>
				Wed
			</tr>
			<tr>
				Thurs
			</tr>
			<tr>
				Fri
			</tr>
			<tr>
				Total
			</tr>
		</thead>
		<tbody>
			@foreach($logTimes as $log)
			
			@endforeach
		</tbody>
	</table>


@stop