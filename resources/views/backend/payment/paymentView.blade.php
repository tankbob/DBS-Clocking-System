@extends('backend.includes.layout')

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatables').DataTable();
        } );

        $(document).ready(function(){
            $('#date-select').on('change', function(){
                $('#viewdate').submit();
            });
        });
    </script>
    
@stop

@section('title')
	Payment
@stop


@section('content')
    
    @include('backend.includes.errors')
	
	<div class="add-user-form col-sm-12">
    	<div class="col-sm-3 no-side-padding">
			<h1 class="menu-h1">WEEK START:</h1>
		</div>
		<div class="col-sm-9">
			{!! Form::open(['method' => 'GET', 'id' => 'viewdate']) !!}

            <select name="date" id='date-select' class="fancy-select">
                @foreach($dates as $d)
                    <option value="{{$d}}" @if($d == @$fromDate) selected="selected" @endif>{{date('d/m/y', strtotime($d))}}</option>
                @endforeach 
            </select>

        {!! Form::close() !!}
		</div>

		<div style="clear:both; height: 10px; width: 100%;"></div>

		<div class="col-sm-3 no-side-padding">
			<h1 class="menu-h1">EXPORT CSV:</h1>
			<div class="quote">
				*Export full CSV for all
					Operatives this week
			</div>
		</div>

		<div class="col-sm-9">
			{!! Form::open(['url' => '/admin/payment/paymentcsv', 'method' => 'post']) !!}
                <input class="hidden" name="fromDate" value="{{$fromDate}}">
				<button class="download-btn"></button>
			{!! Form::close() !!}
		</div>
	</div>

    <div class="row">
        <div class="col-sm-12">
            <h1>OPERATIVES WORKSHEET LIST:</h1>

            <div>
                <div class="col-xs-6 table-next">
                    @if(Input::get('page') > 1)
                        <a href="{{ $payment->previousPageUrl() }}">&lt; Previous Page</a>
                    @endif
                    @if($payment->hasMorePages())
                        <a href="{{ $payment->nextPageUrl() }}">Next Page &gt;</a>
                    @endif
                </div>
                <div class="col-xs-6 text-right table-select">
                    Page:
                    <select onchange="window.location = this.options[this.selectedIndex].value">
                    @for ($i = 1; $i <= $payment->lastPage(); $i++)
                        <option value="{{ Request::url() }}?page={{ $i }}" @if(Input::get('page') == $i) selected="selected" @endif>{{ sprintf('%02d', $i) }} of {{ sprintf('%02d', $payment->lastPage()) }}</option>
                    @endfor
                    </select>
                </div>
            </div>

            <table class="table-responsive table-bordered table-hover dbs-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Telephone</th>
                        <th width="20%" class="text-center">Foreman Approved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payment as $p)
                    	<tr>
                    		<td class="paddingLeft">
                    			<a href="/admin/payment/{{$p->user_id}}?date={{$fromDate}}">{{ $p->name }}</a>
                    		</td>
                    		<td class="paddingLeft">
                    			<a href="/admin/payment/{{$p->user_id}}?date={{$fromDate}}">{{ $p->telephone }}</a>
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

@stop