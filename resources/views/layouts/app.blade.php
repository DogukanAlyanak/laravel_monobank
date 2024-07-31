<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title') - Game</title>

    <link rel="stylesheet" href="{{ asset('plugin/bootstrap-5.0.2/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('main.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugin/bootstrap-5.0.2/js/bootstrap.min.js') }}"></script>
</head>
<body>
    @yield('content')

</body>
</html>
