<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .column {
            float: left;
            font-size: 10px;
            vertical-align: bottom;
        }

        .top-left{
            width: 20%;
        }

        .top-middle {
            width: 45%;

        }

        .top-right {
            width: 35%;

        }

        .row-2 {
            margin-top: 0px;
            padding-top: 65px;
        }

        .row-2 .col-1{
            width: 45%;
            border: 1px solid black;
            margin-left: 10px;
            padding: 5px;
            height: 120px;
        }

        .row-2 .col-2{
            width: 45%;
            border: 1px solid black;
            margin-left: 10px;
            padding: 5px;
            height: 120px;
        }

        .bill-to {
            padding-left:10px;
        }



        .ap-cell{
            border-bottom: 1px solid grey;
        }

        .row-3 {
            width: 100%;
            margin-top: 0px;
            padding-top: 200px;
        }

        .row-3 .col-1 {
            width: 48%;
            margin-left: 10px;
            padding: 0px;
            height: 60px;
        }

        .row-3 .col-2 {
            width: 48%;
            margin-left: 10px;
            padding: 0px;
            height: 60px;
        }

        .row-4 {
            padding-top: 20px;
            font-size: 12px;
        }

        .row-5 {
            margin-top: 10px;
            font-size: 12px;
        }

        .border{
            padding: 5px;
            border: 1px solid black;
        }

        .row-6{
            margin-top: 10px;
            font-size: 12px;
        }

        .row-7{
            margin-top: 10px;
            font-size: 12px;
        }

        .col-2-2 {
            width: 50%;
            border: black;
            border-width: thin;
        }

        .mb-0{
            margin-bottom: 0px;
            padding-bottom: 0px;
        }

        .mb-1{
            margin-bottom: 1px;
        }

        .mb-2{
            margin-bottom: 2px;
        }
    </style>
</head>
<body style="display: grid;">
<div style="width: 100%" style="font-size: 0.8em;">
    <div class="column top-left">
        <img src="{{public_path('/img/pc_logo.png')}}" style="width: 100px;">
    </div>
    <b style="font-size:14px;">PUTNAM CITY PUBLIC SCHOOLS</b><br><br>
    <div class="column top-middle">
        School Activity Fund<br>
        Requisition/Purchase Order
    </div>
    <div class="column top-right">
        <b>Requisition Date:</b> <span class="req_date req_data">{{$po->Date}}</span><br>
        <b>Purchase No.:</b> {{$po->PONumber}}<br>
    </div>
</div>
<div style="width: 100%" class="row-2">

    <div class="column col-1">
        <table style="">
            <tr>
                <td>
                    <b>Vendor Information:</b>
                </td>
                <td>
                    <b>Vendor #:</b> {{$po->VendorID}}
                </td>
            </tr>
            <tr>
                <td>
                    {{$po->Vendor}}<br>
                    {{$po->VendorAddress1}}<br>
                    {{$po->VendorAddress2}}<br>
                    {{$po->VendorCity}}, {{$po->VendorState}} {{$po->VendorZip}} - {{$po->VendorZipPlus}}
                </td>
            </tr>
            <tr>
                <td>
                    <b>Phone</b>
                    {{$po->VendorPhone}}<br>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Fax</b>
                    {{$po->VendorFax}}
                </td>
            </tr>
        </table>
    </div>
    <div class="column col-2">
        <span style="font-weight: bold;">Ship To & Bill To:</span><br><br>
        <span class="bill-to-2">{{$po->Attn}}</span><br>
        <span class="bill-to-2">{{$po->ShippingCompany}}</span><br>
        <span class="bill-to-2">{{$po->ShippingAddress}}</span><br>
        <span class="bill-to-2">{{$po->ShippingCity}}, {{$po->ShippingState}} {{$po->ShippingZip}} - {{$po->ShippingZipPlus}}</span><br>
        <span class="bill-to-2"><b>Phone</b> {{$po->ShipToPhone}}</span><br>
        <span class="bill-to-2"><b>Fax</b> </span>
    </div>
</div>

<div class="row-3">
    <div class="column col-1">
        <p>
            <b>Requested By:</b> <span style="text-decoration: underline;">{{$po->RequestedBy}}</span>
        </p>
        <p>
            <b>Project - Sub Acct - Site:</b> <span style="text-decoration: underline;">{{$po->ProjectCode_Sub}}</span>
        </p>
    </div>
</div>
<div class="row-3">
    <div class="column col-2">
        <p>
            <b>School Activity Fund Custodian:</b> <span style="text-decoration: underline;">{{$po->Custodian}}</span>
        </p>
        <p>
            <b>Account:</b> <span style="text-decoration: underline;">{{$po->a_ProjectName}}</span>
        </p>
    </div>
</div>

<div style="width: 100%" class="row-4">
    <p><b>Notes:</b></p>
    <table style="width:100%; border: 1px solid black; height: 50px;">
        <tr>
            <td style="text-align: left; vertical-align: text-top;">
                {{(strlen($po->Note) > 0) ? $po->Note : "No Notes Available." }}
            </td>
        </tr>
    </table>
</div>
<div class="row-4">
    <table style="width:100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td class="border" style="font-weight: bold;">Qty</td>
            <td class="border" style="font-weight: bold;">Item Description</td>
            <td class="border" style="font-weight: bold;">Unit price</td>
            <td class="border" style="font-weight: bold;">Total</td>
            <td class="border" style="font-weight: bold;">Func</td>
            <td class="border" style="font-weight: bold;">Obj</td>
            <td class="border" style="font-weight: bold;">Prg</td>
            <td class="border" style="font-weight: bold;">Subj</td>
        </tr>
        @foreach($po->requisitionItems as $i)
            <tr>
                <td class="border">{{$i->QtyAmt}}</td>
                <td class="border">{{$i->Description}}</td>
                <td class="border">${{number_format($i->unitPrice, 2, '.', ',')}}</td>
                <td class="border">${{number_format($i->Total, 2, '.', ',')}}</td>
                <td class="border">{{$i->Function}}</td>
                <td class="border">{{$i->Object}}</td>
                <td class="border">{{$i->Program}}</td>
                <td class="border">{{$i->Subject}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>

</html>
