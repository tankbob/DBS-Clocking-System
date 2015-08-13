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

    <div class="row main-form">
        <div class="col-xs-12">
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
        </div>
    </div>
    <h1>
        @if($page == 'users')
           USER LIST:
        @else
            OPERATIVE LIST:
        @endif
     </h1>
    <div class="row">
        <div>
            <div class="col-xs-6 table-next">
                @if(Input::get('page') > 1)
                    <a href="{{ $users->previousPageUrl() }}">&lt; Previous Page</a>
                @endif
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}">Next Page &gt;</a>
                @endif
            </div>
            <div class="col-xs-6 text-right table-select">
                Page:
                <select onchange="window.location = this.options[this.selectedIndex].value">
                @for ($i = 1; $i <= $users->lastPage(); $i++)
                    <option value="{{ Request::url() }}?page={{ $i }}" @if(Input::get('page') == $i) selected="selected" @endif>{{ sprintf('%02d', $i) }} of {{ sprintf('%02d', $users->lastPage()) }}</option>
                @endfor
                   
                </select>
            </div>
        </div>
        <div class="col-xs-12">
            <table class="table-responsive table-bordered table-hover dbs-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="85%">Name</th>
                        <th class="text-center">Delete</th>
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