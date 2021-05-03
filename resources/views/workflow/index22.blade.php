@extends('layouts.main')
@section('content')
    <workflow-table-2-22 imgUrl="{{asset('/img/pc_logo.png')}}" :auth-user="{{$workflowUser}}"></workflow-table-2-22>
@endsection
