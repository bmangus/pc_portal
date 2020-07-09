<html>
@if(isset($status) && $status === 1)
    <h5 style="text-align: center; margin-top: 50px;">
        <b>Budget Workflow 2021 Requisition {{$status}}</b>
    </h5>
    <p style="text-align: center;">
        PO #{{$ponum}} was successfully {{$status}}
    </p>
@else
    <p style="text-align: center; margin-top: 50px;">
        {{$message}}
    </p>
@endif

</html>
