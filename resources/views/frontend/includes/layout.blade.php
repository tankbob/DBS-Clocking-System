<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>
        @yield('title')
    </title>

    <link rel="stylesheet" type="text/css" href="/css/global.css">


    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    @yield('scripts')
    
</head>
<body>

    @include('frontend.includes.header')

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

    @include('frontend.includes.footer')
</body>
</html>
