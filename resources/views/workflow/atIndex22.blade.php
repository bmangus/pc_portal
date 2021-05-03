@extends('layouts.main')
@section('content')
    <at-workflow-table-22 imgUrl="{{asset('/img/pc_logo.png')}}" :auth-user="{{$workflowUser}}"></at-workflow-table-22>
@endsection
