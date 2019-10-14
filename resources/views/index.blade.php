<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link type="text/css" src="{{asset('css/app.css')}}"/>
</head>
<body>
<div id="app">
    <example-component></example-component>
</div>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
