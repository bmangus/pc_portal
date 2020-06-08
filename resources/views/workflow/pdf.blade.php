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
                width: 40%;
            }

            .row-2 .col-2{
                width: 55%;
                border: 1px solid black;
                margin-left: 10px;
                padding: 10px;
                height: 216px;
            }

            .bill-to {
                padding-left:10px;
            }

            .bill-to-2{
                padding-left:45px;
            }

            .ap-cell{
                border-bottom: 1px solid grey;
            }

            .row-3 {
                font-size: 12px;
            }

            .row-4 {
                margin-top: 10px;
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
            <b style="font-size:14px;">PUTNAM CITY PUBLIC SCHOOLS ACCOUNTS PAYABLE</b><br><br>
            <div class="column top-middle">
                5401 NW 40th  - Oklahoma City, OK 73122<br>
                Phone:(405) 495-5200  Fax (405) 495-8648<br>
                FINAL APPROVAL MUST BE OBTAINED PRIOR TO PURCHASE
            </div>
            <div class="column top-right">
               <b>Requisition Date:</b> <span class="req_date req_data">{{$po->Date}}</span><br>
                <b>Purchase No.:</b> {{$po->PONumber}}<br>
                <b>Created By:</b>
            </div>
        </div>
        <div style="width: 100%" class="row-2">

            <div class="column col-1">
                <table style="border: 1px solid black; width: 100%; padding: 5px; height: 100px;">
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
                                    {{$po->VendorAddress}}<br>
                                     {{$po->VendorCity}}, {{$po->VendorState}} {{$po->VendorZip}}
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
                <table style="border: 1px solid black; margin-top: 10px; width: 100%; padding: 5px; height: 100px;">
                    <tr>
                        <td style="background-color: black; color: white;">
                            Ship To:
                        </td>
                    </tr>
                    <tr>
                        <td>{{$po->Attn}}<br>{{$po->ShippingCompany}}<br>{{$po->ShippingAddress}}<br>{{$po->ShippingCity}}, {{$po->ShippingState}} {{$po->ShippingZip}}</td>
                    </tr>
                    <tr>
                        <td>
                           <b>Phone</b>
                            {{$po->ShipToPhone}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Fax</b>
                            {{$po->ShipFax}}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="column col-2">
                <span style="font-weight: bold;">Bill To:</span>
                <span class="bill-to">{{$po->BillToAttention}}</span><br>
                <span class="bill-to-2">{{$po->BillToName}}</span><br>
                <span class="bill-to-2">{{$po->BillToAddress}}</span><br>
                <span class="bill-to-2">{{$po->BillToCity}}, {{$po->BillToState}} {{$po->BillToZip}}</span><br>
                <table style="width:100%; margin-top:25px;">
                    <tr>
                        <td style="width:25px;">
                            1st
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedStatus1}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedBy1}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedDate1}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2nd
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedStatus2}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedBy2}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedDate2}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3rd
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedStatus3}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedBy3}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedDate3}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4th
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedStatus4}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedBy4}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedDate4}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5th
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedStatus5}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedBy5}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedDate5}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            TE
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedStatusTE}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedByTE}}
                        </td>
                        <td class="ap-cell">
                            {{$po->ApprovedDateTE}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Final
                        </td>
                        <td class="ap-cell">
                            {{$po->FinalApprovedStatus}}
                        </td>
                        <td class="ap-cell">
                            {{$po->FinalApprovedBy}}
                        </td>
                        <td class="ap-cell">
                            {{$po->FinalApprovedDate}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="width: 100%" class="row-3">
            <b>Instructions:</b>
            <table style="width:100%; border: 1px solid black; height: 50px;">
                <tr>
                    <td style="text-align: left; vertical-align: text-top;">
                        {{(strlen($po->Instructions) > 0) ? $po->Instructions : "No Instructions Available." }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="row-4">
            <table style="width:100%;" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td class="border" style="font-weight: bold;">FY</td>
                    <td class="border" style="font-weight: bold;">Fund</td>
                    <td class="border" style="font-weight: bold;">Project</td>
                    <td class="border" style="font-weight: bold;">Function</td>
                    <td class="border" style="font-weight: bold;">Program</td>
                    <td class="border" style="font-weight: bold;">Subject</td>
                    <td class="border" style="font-weight: bold;">Ship Site</td>
                    <td class="border" style="font-weight: bold;">Site</td>
                    <td class="border" style="font-weight: bold;">Encumbrance No.</td>
                </tr>
                <tr>
                    <td class="border">{{$po->FiscalYear}}</td>
                    <td class="border">{{$po->Fund}}</td>
                    <td class="border">{{$po->Project}}</td>
                    <td class="border">{{$po->Function}}</td>
                    <td class="border">{{$po->Program}}</td>
                    <td class="border">{{$po->Subject}}</td>
                    <td class="border">{{$po->JobClass}}</td>
                    <td class="border">{{$po->Site}}</td>
                    <td class="border">{{$po->PONumber}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row-5">
            <table style="width:100%;" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="border" style="font-weight: bold;">Qty</td>
                    <td class="border" style="font-weight: bold;">Fixed Asset</td>
                    <td class="border" style="font-weight: bold;">Item No</td>
                    <td class="border" style="font-weight: bold;">Item Description</td>
                    <td class="border" style="font-weight: bold;">Object Code</td>
                    <td class="border" style="font-weight: bold;">Unit price</td>
                    <td class="border" style="font-weight: bold;">Total</td>
                </tr>
                @foreach($po->requisitionItems as $i)
                    <tr>
                        <td class="border">{{$i->QtyAmt}}</td>
                        <td class="border">{{$i->FixedAsset}}</td>
                        <td class="border">{{$i->ItemCount}}</td>
                        <td class="border">{{$i->Description}}</td>
                        <td class="border">{{$i->Object}}</td>
                        <td class="border">{{$i->UnitPrice}}</td>
                        <td class="border">{{$i->Total}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="row-6">
            <b>Charge To:</b> {{$po->ChargeTo}}
        </div>




<!--
                    <div class=" m-2 p-2 border-2 border-black">
                        <p class="bg-black text-white p-1 mb-3">Ship To:</p>
                        <span class="vendor_info req_data"></span>
                        <p class="clear_all"></p>
                        <p class="comm">

                        </p>
                    </div>
                </div>
                <div class="w-3/5 m-2 p-2 border-2 border-black">
                    <div class="flex w-full mb-4">
                        <div class="w-1/6 text-center">

                        </div>
                        <div class="w-5/6">
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">

                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex w-full mx-2 px-2">
                <span>Instructions</span>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2 p-2 border-2 border-black">
                        "($po->Instructions.length < 1) ? "No instructions available." : $po.Instructions"
                    </div>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2">

                    </div>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2">
                        <table class="table-fixed border-2 border-top-table border-black">
                            <tbody>
                            <tr>

                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <p class="foot" style="font-size:14px"></p>
            <p>Comments Go Here.....</p>

        </div>
-->
    </body>

</html>
