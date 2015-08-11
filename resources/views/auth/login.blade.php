@extends('frontend.includes.layout')

@section('title')
    Login
@stop

@section('footer-logo')
    <img src="{{ url('images/approvedlogo.svg') }}" class="footer-login-logo" id="footer-login-logo">
@stop

@section('body-class') page-login @stop

@section('content')
    <div class="col-xs-12 login-form">
        @include('frontend.includes.errors')
        <form method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <div class="text-center">
                <input type="email" name="email" value="{{ old('email') }}" class="login-input" placeholder="Email">
            </div>

            <div class="text-center">
                <input type="password" name="password" id="password" class="login-input" placeholder="Password" style="margin-top: 10px;">
            </div>

            <input class="hidden" name="loginType" value="Operative">

            <div class="text-center">
                <button class="submit-btn"></button>
            </div>
        </form>
    </div>
@stop