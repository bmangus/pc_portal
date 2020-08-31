<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <navbar-mobile asset="{{asset('img/PC_Type_1Line_2C.png')}}" user="{{$user}}"></navbar-mobile>
        <div class="flex w-100 h-screen mx-auto">
            <navbar-desktop user="{{$user}}"></navbar-desktop>
            <div class="flex w-full lg:w-5/6 h-full shadow-inner bg-gray-300">
                @yield('content')
            </div>
        </div>
    </div>
    @if(config('app.env') == 'local')
        <script src="http://localhost:35729/livereload.js"></script>
    @endif
</body>
<!--<navbar imageLocation="{{asset('img/PC_Type_1Line_2C.png')}}"></navbar>
</div>-->
