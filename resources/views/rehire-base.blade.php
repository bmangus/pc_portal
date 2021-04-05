@extends('layouts.app')
@section('content')
    @livewire('rehire-table',['user'=>$user, 'schoolMap'=>$schoolMap, 'schoolNames'=>$schoolNames])
@endsection
