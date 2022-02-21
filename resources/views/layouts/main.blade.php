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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
    @livewireScripts
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/lates
  t/toastr.min.js"></script>

    <script>
        window.addEventListener('alert', event => {
            toastr[event.detail.type](event.detail.message,
                event.detail.title ?? '') toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }
        })
    </script>
    <div id="app">
        <navbar-mobile asset="{{asset('img/PCS_PrimaryLogo_svg.svg')}}" user="{{$user}}"></navbar-mobile>
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
