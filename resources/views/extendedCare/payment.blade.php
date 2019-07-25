<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <Title>PC Portal - Extended Care</Title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:200,600" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-blue-100">
<div class="mx-auto">
    <div class="max-w-5xl rounded flex p-8 mx-auto bg-white align-content-center content-between shadow-md">
        <img src="{{asset('img/pc_logo.png')}}" alt="PC Logo"/>
        <div class="mt-2 ml-3">
            <p class="text-3xl mx-auto p-2">Putnam City Schools</p>
            <p class="text-2xl mx-auto p-2 text-gray-600">Extended Care Registration Form</p>
        </div>
    </div>
    <div class="max-w-5xl rounded overflow-hidden shadow-md bg-white mx-auto p-8 px-6 mt-8">
        <p>Your Account Number is: {{$account}}.</p><p> Please make note of this account number. You will need it to process the payment for this registration and future payments.</p><p>Please click <a class="underline text-blue-500" href="https://www.putnamcityschools.org/schools/extended-care/payment">here</a> to pay the registration fee (required).</p>
    </div>
</div>
</body>
</html>
