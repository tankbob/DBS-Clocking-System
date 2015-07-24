{!! Form::open(['method' => 'POST', 'id' => 'viewdate']) !!}

	<select name="date" id='date-select' class="fancy-select">
		@foreach($dates as $d)
			<option value="{{$d}}" @if($d == @$fromDate) selected="selected" @endif>{{date('d/m/y', strtotime($d))}}</option>
		@endforeach	
	</select>

{!! Form::close() !!}

<script type="text/javascript">
	$(document).ready(function(){
		$('#date-select').on('change', function(){
			$('#viewdate').submit();
		});
	});
</script>