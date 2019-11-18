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
        <navbar asset="{{asset('img/PC_Type_1Line_2C.png')}}" user="{{$user}}"></navbar>
        <div class="flex w-100 h-screen mx-auto">
            <div class="w-1/6 h-full bg-gray-800 hidden lg:flex">
                <ul class="flex-col w-full pt-2">
                    <li class="bg-gray-800 w-full mx-auto py-2 px-auto text-center justify-center hover:bg-gray-700 focus:bg-gray-900 text-white">Dashboard</li>
                    <li class="bg-gray-800 w-full mx-auto py-2 px-auto text-center justify-center hover:bg-gray-700 focus:bg-gray-900 text-white">Budget Tracker</li>
                    <li class="bg-gray-800 w-full mx-auto py-2 px-auto text-center justify-center hover:bg-gray-700 focus:bg-gray-900 text-white">Activity Tracker</li>
                </ul>
            </div>
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
