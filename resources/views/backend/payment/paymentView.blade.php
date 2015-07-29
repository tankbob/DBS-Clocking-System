@extends('backend.includes.layout')

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatables').DataTable();
        } );
    
        function submitForm(id){
            $('#form-'+id).submit();
        }

    </script>
    
@stop

@section('title')
	Payment
@stop


@section('content')
	
	<div class="add-user-form col-sm-12">
    	<div class="col-sm-3 col-sm-offset-1">
			<h1 class="menu-h1">
				WEEK START:
			</h1>
		</div>
		<div class="col-sm-8">
			@include('backend.includes.date')
		</div>

		<div style="clear:both; height: 10px; width: 100%;"></div>

		<div class="col-sm-3 col-sm-offset-1">
			<h1 class="menu-h1">
				EXPORT CSV:
			</h1>
			<div class="quote">
				*Export full CSV for all
					Operatives this week
					OPERATIVES WORKSHEET LIST:
			</div>
		</div>

		<div class="col-sm-8">
			{!! Form::open(['url' => '/admin/payment/paymentcsv', 'method' => 'post']) !!}
                <input class="hidden" name="fromDate" value="{{$fromDate}}">
				<button class="download-btn"></button>
			{!! Form::close() !!}
		</div>

		</div>
	</div>

	<div class="col-xs-12 col-xs-offset1">
        <h1 class="col-xs-offset-1">
        	OPERATIVES WORKSHEET LIST:
         </h1>
        <div class="col-sm-8 col-sm-offset-2">
            <table id="datatables" class="table-responsive table-bordered table-hover"  class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Telephone
                        </th>
                        <th>
                            Foreman Approved
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payment as $p)

                    	<tr>
                    		<td class="paddingLeft">
                    			<a href="#" onclick="submitForm({{$p->user_id}})">
	                    			{{ $p->name }}
	                    		</a>
                    		</td>
                    		<td class="paddingLeft">
                    			<a href="#" onclick="submitForm({{$p->user_id}})">
                    				{{ $p->telephone }}
                    			</a>
                    		</td>
                    		<td class="text-center">
                    			@if($p->approved)
                    				<div class="success-btn center-div"></div>
                    			@else
                    				<div class="cross-btn center-div"></div>
                    			@endif
                    		</td>
                    	</tr>
                        
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>

    @foreach($payment as $p)
        {!!Form::open(['url' => '/admin/payment/'.$p->user_id, 'method' => 'POST', 'id' => "form-".$p->user_id])!!}
            <input class="hidden" name="date" value="{{$fromDate}}">
        {!!Form::close()!!}
    @endforeach

@stop