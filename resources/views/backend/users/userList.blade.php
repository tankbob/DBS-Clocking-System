@extends('backend.includes.layout')

@section('title')
	@if($page == 'users')
		Users
	@else
		Operatives
	@endif
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatables').DataTable();
        } );
    </script>
    
@stop

@section('content')

	@include('frontend.includes.errors')

    <div class="add-user-form col-sm-12">
    	<div class="col-sm-3 col-sm-offset-1">
    		<h1>
    			ADD USER:
    		</h1>
    	</div>
    	
    	<div class="col-sm-8 login-form">
            
            {!! Form::open(['url' => '/admin/users', 'method' => 'POST']) !!}

                <div class="form-group">
                	<div class="col-xs-3">
    	            	<label for="name">Name:</label>
                	</div>
                	<div class="col-xs-9">
    	                <input type="name" name="name" value="{{ old('name') }}" class="login-input">	            		
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-3">
    	            	<label for="email">Email:</label>
                	</div>
                	<div class="col-xs-9">
    	                <input type="email" name="email" value="{{ old('email') }}" class="login-input">	            		
                	</div>
                </div>

                <div class="form-group">
                	<div class="col-xs-3">
    	            	<label for="password">Password:</label>
                	</div>
                	<div class="col-xs-9">
    	                <input type="password" name="password" id="password" class="login-input">
                	</div>
                </div>

                <input class="hidden" name="loginType" value="Admin">

                <div class="text-center">
                    <button class="submit-btn">

                    </button>
                </div>
               
            {!! Form::close() !!}
        </div>
    </div>

    <div class="col-xs-12 col-xs-offset1">
        <h1 class="col-xs-offset-1">
            USER LIST:
         </h1>
        <div class="col-sm-8 col-sm-offset-2">
            <table id="datatables" class="table-responsive table-bordered table-hover"  class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="75%">
                            Name
                        </th>
                        <th>
                            Delete
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="paddingLeft">
                                <a href="/admin/users/{{$user->id}}/edit">{{$user->name}}</a>
                            </td>
                            <td class="text-center">
                                @if($user->id != Auth::user()->id)
                                    {!! Form::open(['url' => "/admin/users/$user->id", 'method' => 'DELETE']) !!}
                                        <button class="cross-btn"></button>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear-float"></div>
@stop