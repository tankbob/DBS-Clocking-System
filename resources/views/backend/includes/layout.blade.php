<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>
        @yield('title')
    </title>

    <link rel="stylesheet" type="text/css" href="/css/admin.css">


    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="/js/bootstrap.min.js"></script>

    <script type="text/javascript">
       document.write('\x3Cscript src="/js/fonts.js" type="text/javascript">\x3C/script>');
    </script>

    @yield('scripts')
    
</head>
<body>

    @include('backend.includes.header')

    @include('backend.includes.menu')

    <div class="container">
        @yield('content')
    </div>

    @include('backend.includes.footer')


</body>
</html>
