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

        function deleteUser(id){
            event.preventDefault();
            if(confirm("Are you sure you want to delete this user? All information will be lost.")){
                $('#deleteForm'+id).submit();
            }
        }
    </script>
@stop

@section('content')

	@include('backend.includes.errors')

	<div class="col-sm-5">
		<h1>
            @if($page == 'users')
		      	ADD USER:
            @else
                ADD OPERATIVE:
            @endif
		</h1>
	</div>
	
	<div class="col-sm-7 user-form">
        @if($page == 'users')
            {!! Form::open(['url' => 'admin/users', 'method' => 'post', 'class' => 'form-horizontal']) !!}
        @else
            {!! Form::open(['url' => 'admin/operatives', 'method' => 'post', 'class' => 'form-horizontal']) !!}
        @endif

            <div class="form-group">
            	<div class="col-sm-3 no-side-padding">
	            	<label for="name">Name:</label>
            	</div>
            	<div class="col-sm-9 col-sm-12">
	                <input type="text" name="name" value="{{ old('name') }}" class="login-input">	            		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-sm-3 no-side-padding">
	            	<label for="email">Email:</label>
            	</div>
            	<div class="col-sm-9 col-sm-12">
	                <input type="email" name="email" value="{{ old('email') }}" class="login-input">	            		
            	</div>
            </div>

            <div class="form-group">
            	<div class="col-sm-3 no-side-padding">
	            	<label for="password">Password:</label>
            	</div>
            	<div class="col-sm-9 col-sm-12">
	                <input type="password" name="password" id="password" class="login-input">
            	</div>
            </div>

            @if($page == 'operatives')
                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="telephone">Telephone:</label>
                    </div>
                    <div class="col-sm-9 col-sm-12">
                        <input type="telephone" name="telephone" value="{{ old('telephone') }}" class="login-input">                        
                    </div>
                </div>
            @endif

            <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
                <button class="submit-btn"></button>
            </div>
            </div>
           
        {!! Form::close() !!}
    </div>

    <h1 class="col-xs-12">
        @if($page == 'users')
           USER LIST:
        @else
            OPERATIVE LIST:
        @endif
     </h1>
    <div class="row">
        <div class="col-xs-12">
    <table id="datatables" class="table-responsive table-bordered table-hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="75%">Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="paddingLeft">
                        @if($page == 'users')
                            <a href="/admin/users/{{$user->id}}/edit">{{$user->name}}</a>
                        @else
                            <a href="/admin/operatives/{{$user->id}}/edit">{{$user->name}}</a>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($user->id != Auth::user()->id)
                            @if($page == 'users')
                                {!! Form::open(['url' => "/admin/users/$user->id", 'method' => 'DELETE', 'id' => 'deleteForm'.$user->id]) !!}
                            @else
                                {!! Form::open(['url' => "/admin/operatives/$user->id", 'method' => 'DELETE', 'id' => 'deleteForm'.$user->id]) !!}
                            @endif
                                {!! Form::close() !!}
                                <a href="#" class="cross-btn" onclick="deleteUser({{$user->id}})"></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
        
    
    <div class="clear-float"></div>
    @foreach($users as $user)
        @if($user->id != Auth::user()->id)
            @if($page == 'users')
                {!! Form::open(['url' => "/admin/users/$user->id", 'method' => 'DELETE', 'id' => 'deleteForm'.$user->id]) !!}
            @else
                {!! Form::open(['url' => "/admin/operatives/$user->id", 'method' => 'DELETE', 'id' => 'deleteForm'.$user->id]) !!}
            @endif
            {!! Form::close() !!}
        @endif
    @endforeach
@stop