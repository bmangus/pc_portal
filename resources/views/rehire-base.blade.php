@extends('layouts.app')
@section('content')
    @livewire('rehire-group',['user'=>$user, 'schoolMap'=>$schoolMap, 'schoolNames'=>$schoolNames])
@endsection
