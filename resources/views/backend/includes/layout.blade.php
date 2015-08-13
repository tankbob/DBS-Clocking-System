<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:600,400,300">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="/css/admin.css">
    <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
</head>
<body class="@yield('body-class')">
    <div class="header text-center-sm">
        <div class="headerContainer container">
            <img class="headerLogo" src="/images/dbs_logo.jpg" width="178" height="107" alt="DBS Contracts" data-mu-svgfallback="images/dbslogo_poster_.png">
            @if(Auth::user())
                <div>
                    {!! Form::open(['url' => '/auth/logout', 'method' => 'GET']) !!}
                        <input type="text" class="hidden" name="page" value="admin">
                        <button class="logout-btn" href="/auth/logout"></button>
                    {!! Form::close() !!}
                </div>
            @endif
        </div>
    </div>

    <div class="menu text-center">
        <div class="container">
            <div class="row">
                <div class="col-xs-2 col-sm-offset-1">
                    <a href="/admin/users" class="menuLink usersLink @if(isset($page) && $page == 'users') usersSelected @endif @if(!Auth::user()) not-active @endif"></a>
                </div>
                <div class="col-xs-2">
                    <a href="/admin/operatives" class="menuLink operativeLink @if(isset($page) && $page == 'operatives') operativeSelected @endif @if(!Auth::user()) not-active @endif"></a>
                </div>
                <div class="col-xs-2">
                    <a href="/admin/hours" class="menuLink hoursLink @if(isset($page) && $page == 'hours') hoursSelected @endif @if(!Auth::user()) not-active @endif"></a>
                </div>
                <div class="col-xs-2">
                    <a href="/admin/payment" class="menuLink paymentLink @if(isset($page) && $page == 'payment') paymentSelected @endif @if(!Auth::user()) not-active @endif"></a>
                </div>
                <div class="col-xs-2">
                    <a href="/admin/jobs" class="menuLink jobLink @if(isset($page) && $page == 'jobs') jobSelected @endif @if(!Auth::user()) not-active @endif"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="container">
            <p class="text-muted"> Target Ink &copy; {{ date('Y') }} DBS Contracts Ltd.</p>
        </div>
    </footer>

    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>