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
    <link rel="stylesheet" type="text/css" href="/css/global.css">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon.png">
    <link rel="shortcut icon" sizes="196x196" href="/apple-touch-icon.png">
    <link rel="shortcut icon" sizes="128x128" href="/apple-touch-icon.png">
</head>
<body class="@yield('body-class')">
    <div class="header text-center-sm">
        <div class="container">
            <img class="headerLogo" src="/images/dbslogo.svg" width="173" height="104" alt="DBS Contracts" data-mu-svgfallback="images/dbslogo_poster_.png">

            <div class="pull-right">
                @if(Auth::user())
                    <div>
                        <a class="logout-btn hidden-xs pull-right" href="/auth/logout"></a>
                        <a class="logout-btn-mobile visible-xs pull-right" href="/auth/logout"></a>
                    </div>
                @endif
                <div class="hidden-xs headerPhone">01622 715 919</div>
            </div>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>
    
    <div class="text-center footer container">
        <div class="footer-note">
            <p><span class="orange">Please note:</span> You can edit your detail for today’s date only - all details must have been updated by <span class="orange">12am</span></p>
            <p>For any further information please call  the office on <span class="orange big-text">01622 715 919</span></p>
        </div>

        @section('footer-logo')
            <img src="{{ url('images/approved_logo_colour.svg') }}" class="footer-login-logo" id="footer-login-logo">
        @show

        <div class="footer-copy">Application by Target Ink &copy; DBS Contracts {{ date("Y") }}</div>
    </div>

    <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>