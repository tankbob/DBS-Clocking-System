<div class="date-picker">
	<div class="form-group text-center">
		{!! Form::open(['url' => '/viewdate', 'method' => 'POST', 'id' => 'viewdate']) !!}
			{!! Form::label('date', 'Date:') !!}

			<select name='date' id='date-select'>
				@for($iDay = 6; $iDay > 0; $iDay--)
					<option value="{{ date('Y-m-d', strtotime("-" . $iDay . " day")) }}">
						{{ date('d/m/y', strtotime("-" . $iDay . " day")) }}
					</option>
				@endfor
					<option @if(!isset($date)) selected="selected" @endif>
						{{ date('d/m/y') }}
					</option>
			</select>
		{!! Form::close() !!}
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#date-select').on('change', function(){
			$('#viewdate').submit();
		});
	});
</script>