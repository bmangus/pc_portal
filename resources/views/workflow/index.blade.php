@extends('layouts.main')
@section('content')
    <workflow-table imgUrl="{{asset('/img/pc_logo.png')}}" :auth-user="{{$workflowUser}}"></workflow-table>
@endsection
