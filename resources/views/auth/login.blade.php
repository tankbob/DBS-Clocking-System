@extends('frontend.includes.layout')

@section('title')
    Login
@stop

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/css/login.css">
    <script type="text/javascript">
        $(document).ready(function(){
            $('#footer-login-logo').attr('src', '/images/approvedlogo.svg');
        });
    </script>
@stop

@section('content')
    <div class="col-xs-12 login-form">
        
        @include('frontend.includes.errors')

        <form method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <div class="text-center">
                <input type="email" name="email" value="{{ old('email') }}" class="login-input" placeholder="Email">
            </div>

            <div class="text-center">
                <input type="password" name="password" id="password" class="login-input" placeholder="Password">
            </div>

            <input class="hidden" name="loginType" value="Operative">

            <div class="text-center">
                <button class="submit-btn">
                  
                </button>
            </div>
           
        </form>
    </div>
@stop