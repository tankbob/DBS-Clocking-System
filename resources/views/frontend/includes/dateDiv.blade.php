<div class="date-picker">
	<div class="form-group text-center offset-form-input">
		{!! Form::open(['url' => '/viewdate', 'method' => 'POST', 'id' => 'viewdate']) !!}
			{!! Form::label('date', 'View Date:', ['class' => 'col-xs-6 control-label']) !!}
			<div class="col-xs-6">
				<select name="date" id="date-select">
					@for($iDay = ((1 + date('w')) % 7); $iDay > 0; $iDay--)
						<option value="{{ date('Y-m-d', strtotime("-" . $iDay . " day")) }}" @if(isset($date) && $date == date('Y-m-d', strtotime("-" . $iDay . " day"))) selected="selected" @endif>
							{{ date('d/m/y', strtotime("-" . $iDay . " day")) }}
						</option>
					@endfor
						<option @if(!isset($date)) selected="selected" @endif>
							{{ date('d/m/y') }}
						</option>
				</select>
			</div>
		{!! Form::close() !!}
	</div>
</div>