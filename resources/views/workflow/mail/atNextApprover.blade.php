<div>
    <p style="font-size:18px"><strong>REQUEST FOR APPROVAL</strong></p>
    <p>You have received a request for approval for purchase order # {{$requisition->PONumber}}<br>
        <br>
    </p>
    <p>Click this one use link to approve this purchase order. <a href="{{$approvalLink}}" target="_blank" rel="noopener noreferrer"><b>Approve</b></a><br>
        <br>
        If you would like to review more details or to reject this purchase order, click <a href="{{$btLink}}" target="_blank" rel="noopener noreferrer">here</a> to go to .PC.</p>
    <p>A summary of the purchase request is below...</p>
    <p><strong>Vendor:</strong></p>
    <p style="line-height:1.2; margin:0!important">{{$requisition->Vendor}}</p>
    <p style="line-height:1.2; margin:0!important"><span tabindex="0">{{$requisition->VendorAddress1}}</span></p>
    <p style="line-height:1.2; margin:0!important"><span tabindex="0">{{$requisition->VendorCity}}, {{$requisition->VendorState}} {{$requisition->VendorZip}}</span></p>
    <p>{{$requisition->AccountCode}}<br>
        Charge To: </p>
    <p><strong>Items requested:</strong></p>
    <table cellpadding="4" border="0">
        <tbody>
        <tr>
            <th width="80px" style="text-align:left">QTY</th>
            <th width="250px" style="text-align:left">DESCRIPTION</th>
            <th width="100px" style="text-align:left">TOTAL</th>
        </tr>
        @foreach($requisition->requisitionItems as $i)
            <tr>
                <td style="font-size:13px!important">{{$i->QtyAmt}}</td>
                <td style="font-size:13px!important">{{$i->Description}} </td>
                <td style="font-size:13px!important">${{number_format($i->Total, 2, '.', ',')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
