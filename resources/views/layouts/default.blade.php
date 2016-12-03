<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Battleship')</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')

    <link rel="shortcut icon" href="/img/radar.jpg"/>

    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"
></script>
<script src="/js/vendor.js" type="text/javascript"></script>

<div class="container">
    @yield('content')
</div>

</body>
</html>
