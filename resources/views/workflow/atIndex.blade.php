@extends('layouts.main')
@section('content')
    <at-workflow-table imgUrl="{{asset('/img/pc_logo.png')}}" :auth-user="{{$workflowUser}}"></at-workflow-table>
@endsection
