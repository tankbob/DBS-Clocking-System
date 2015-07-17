@extends('frontend.includes.layout')

@section('title')
    Login
@stop

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/css/login.css">
@stop

@section('content')
    <div class="col-xs-12 login-form">
        
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <div class="text-center">
                <input type="email" name="email" value="{{ old('email') }}" class="login-input" placeholder="Email">
            </div>

            <div class="text-center">
                <input type="password" name="password" id="password" class="login-input" placeholder="Password">
            </div>

            <div class="text-center">
                <button class="submit-btn">
                  
                </button>
            </div>
           
        </form>
    </div>
@stop