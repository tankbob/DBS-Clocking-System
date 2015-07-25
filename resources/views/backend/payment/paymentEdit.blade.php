@extends('backend.includes.layout')

@section('title')
	Hours per Operative
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#daySelect').on('change', function(){
				$('.timesDiv').addClass('hidden');
				$('#times-'+$('#daySelect').val()).removeClass('hidden');
			});
		});
	</script>
@stop

@section('content')

	<div class="add-user-form col-sm-12">
    	<div class="col-sm-3 col-sm-offset-1">
    		<h1>
                EDIT OPERATIVE HOURS:
    		</h1>
    		<h1>
    			<a @if($page == 'users') href="/admin/payments" @else href="/admin/operatives" @endif>
    				< BACK
    			</a>
    		</h1>
    	</div>
    	
    	<div class="col-sm-7 login-form">

    		<div class="form-group">
            	<div class="col-sm-3">
	            	<label for="name">Operative:</label>
            	</div>
            	<div class="col-sm-9">
	                <input type="text" class="login-input" value="{{$user->name}}" disabled="true">	            		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-sm-3">
	            	<label for="date">Week Start:</label>
            	</div>
            	<div class="col-sm-9">
					@include('backend.includes.date')
				</div>
			</div>

			<div class="form-group">
            	<div class="col-sm-3">
	            	<label for="csv">
						EXPORT CSV:
						<div class="quote">
							*Export CSV for Operatives
						</div>
					</label>
				</div>
				<div class="col-sm-3">
					{!! Form::open() !!}
						<button class="download-btn"></button>
					{!! Form::close() !!}
				</div>
			</div>

    	</div>
    </div>

    <div class="add-user-form col-sm-12">
    	<div class="col-sm-7 col-sm-offset-4 login-form">

    		<div class="form-group">
    			<div class="col-sm-3">
	            	<label for="day">
	            		Day:
	            	</label>
	            </div>
	            <div class="col-sm-9">
	            	<select id='daySelect' class="fancy-select">
	            		<option value="0">Saturday</option>
	            		<option value="1">Sunday</option>
	            		<option value="2">Monday</option>
	            		<option value="3">Tuesday</option>
	            		<option value="4">Wednesday</option>
	            		<option value="5">Thursday</option>
	            		<option value="6">Friday</option>
	            	</select>
	            </div>
    		</div>

    		@foreach($times as $i => $t)
    			<div class="timesDiv @if($i > 0) hidden @endif " id="times-{{$i}}">
		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Hours:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['Mon-Fri']}}
				  			</p>          	
			            </div>
			        </div>

		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Holiday:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['Holiday']}}
				  			</p>          	
			            </div>
			        </div>

		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Overtime:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['overtime']}}
				  			</p>          	
			            </div>
			        </div>

		    		<div class="form-group">
		    			<div class="col-sm-3">
			            	<label>
			            		Weekends:
			            	</label>
			            </div>
			            <div class="col-sm-9">
				  			<p>
					  			{{$t['Weekends']}}
				  			</p>          	
			            </div>
			        </div>
			    </div>
		    @endforeach



    	</div>
    </div>




@stop