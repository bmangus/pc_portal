@extends('layouts.dotPC')
@section('content')
    @livewire('dashboard-view', ['user'=>$user])
@endsection
